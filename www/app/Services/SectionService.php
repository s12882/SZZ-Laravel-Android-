<?php

namespace App\Services;

use App\Enums\DtButtonType;
use App\Helpers\DtButtonHelper;
use App\Models\Section;
use Auth;
use Yajra\Datatables\Datatables;

class SectionService
{

    public function get($id)
    {
        return $this->sections()->where('id', $id)->first();
    }

    public function sections()
    {
        $baseQuery = Section::select();
        if (Auth::user()->hasPermissionTo('see all sections/users/tasks')) {
            $sections = $baseQuery;
        } else if (Auth::user()->hasPermissionTo('see own and slave sections/users/tasks') && Auth::user()->section) {
            $userSection = Auth::user()->section;
            $sections = $baseQuery->whereIn('id', $this->slaveIds($userSection));
        } else {
            $sections = $baseQuery->where('id', Auth::user()->section_id);
        }
        return $sections;
    }

    public function lists()
    {
        return $this->sections()->get()->pluck('name', 'id');
    }

    public function store($input)
    {
        \DB::beginTransaction();
        try {
            $parent = Section::findOrFail($input['parent_id']);
            \DB::statement("UPDATE sections SET `right`=`right`+2 WHERE `right` > $parent->right-1");
            \DB::statement("UPDATE sections SET `left`=`left`+2 WHERE `left` > $parent->right-1");
            $section = Section::create([
                'name' => $input['name'],
                'parent_id' => $parent->id,
                'left' => $parent->right,
                'right' => $parent->right + 1,
            ]);
            \DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            \DB::rollback();
        }
        return $success;
    }

    public function update($input)
    {
        $section = Section::where('id', $input['id'])->first();
        try {
            \DB::beginTransaction();

            $parent = Section::where('id', $input['parent_id'])->first();
            $parent_id = $parent == null ? null : $parent->id;
            $moved = $section->right - $section->left + 1;
            $id = $this->slaveIds($section);
            if($parent)
                if ($parent->left > $section->parent && $parent->right > $section->right) { // w prawo
                    $indexes = abs($parent->right - $section->right);
                    \DB::statement("UPDATE sections SET `left`=`left` - $moved WHERE `left` > $section->right AND `left` < $parent->right");
                    \DB::statement("UPDATE sections SET `right`=`right` - $moved WHERE `right` > $section->right AND`right` < $parent->right");
                    \DB::table('sections')->whereIn('id', $id)->update(['left' => \DB::raw("`left` + $indexes - 1")]);
                    \DB::table('sections')->whereIn('id', $id)->update(['right' => \DB::raw("`right` + $indexes - 1")]);
                } else { // w lewo
                    $indexes = abs($parent->right - $section->left);
                    \DB::statement("UPDATE sections SET `left`=`left` + $moved WHERE `left` > $parent->right AND `left` < $section->left");
                    \DB::statement("UPDATE sections SET `right`=`right` + $moved WHERE `right` >= $parent->right and `right` < $section->left");
                    \DB::table('sections')->whereIn('id', $id)->update(['left' => \DB::raw("`left` - $indexes")]);
                    \DB::table('sections')->whereIn('id', $id)->update(['right' => \DB::raw("`right` - $indexes")]);
                }

            \DB::table('sections')->where('id', $id)->update(['name' => $input['name'], 'parent_id' => $parent_id]);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            echo $e;
            \DB::rollback();
            return false;
        }
    }

    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            $section = Section::where('id', $id)->first();
            if ($section !== null) {
                $ids = $this->slaveIds($section);
                \DB::statement("UPDATE sections SET `left`=`left`- 2 WHERE `left` > $section->right");
                \DB::statement("UPDATE sections SET `right`=`right`- 2 WHERE `right` >= $section->right");
                \DB::table('sections')->where([['parent_id', $section->id]])->update(['parent_id' => $section->parent_id]);
                \DB::table('sections')->whereIn('id', $ids)->update(['left' => \DB::raw("`left` - 1")]);
                \DB::table('sections')->whereIn('id', $ids)->update(['right' => \DB::raw("`right` - 1")]);
                \DB::table('sections')->where([['id', $section->id]])->delete();

            }
        });
        return Section::where('id', $id)->first() == null;
    }

    public function treedata($input)
    {
        $datatables = Datatables::of($this->sections());
            $datatables = $datatables->addColumn('actions', function ($section) {
                $actions = '';
                if (Auth::user()->hasPermissionTo('create section'))
                    $actions .= DtButtonHelper::getByType(route('section.create_with_parent', ['parent' => $section->id]), DtButtonType::CREATE);
                if (Auth::user()->hasPermissionTo('update section'))
                    $actions .= DtButtonHelper::getByType(route('section.edit', ['section' => $section->id]), DtButtonType::EDIT);
                if (Auth::user()->hasPermissionTo('delete section') && $section->parent_id != null)
                    $actions .= DtButtonHelper::getByType(route('section.destroy', ['section' => $section->id]), DtButtonType::DELETE);

                if($actions == '') 
                    $actions = "BRAK OPCJI";
                return $actions;
            })->rawColumns(['actions']);

        return $datatables->make(true);
    }

    public static function slaveIds($section)
    {
        return Section::select('id')->where([['left', ">=", $section->left], ['right', '<=', $section->right]])->pluck('id')->toArray();
    }
}
