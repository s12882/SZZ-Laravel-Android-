package com.example.myfirstapp.Activities;

/**
 * Created by Андрей on 11.10.2017.
 */

import android.app.FragmentTransaction;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.example.myfirstapp.Fragments.StaffFragment;
import com.example.myfirstapp.Fragments.TasksFragment;
import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.SessionService;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;


public class SectionActivity extends BasicActivity implements View.OnClickListener {

    private static final String TAG = SectionActivity.class.getName();
    private String intentFragment;
    private int section_id;
    private TextView taskText, staffText;
    private StaffFragment staffFragment;
    private TasksFragment tasksFragment;
    private FragmentTransaction fTrans;
    private Button btnStaff, btnTasks;
    private User currentUser;
    private Section currentSection;
    SessionService session;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_section, null, false);
        drawer_layout.addView(contentView, 0);


        //------------PODCIĄGANIE INTENTU-----------//
        Intent intent = getIntent();
        intentFragment = getIntent().getExtras().getString("Fragment", "TASK");

        //-------------LADOWANIE SESJI---------------//
        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);
        session.updateSession(currentUser);
        currentSection = (Section)intent.getSerializableExtra("currentSection");

        System.out.println("currentSection!!!" + currentSection.getId());

        setTitle("Dział " + " '" + currentSection.getName() + "' ");

        tasksFragment = TasksFragment.newInstance(currentUser, currentSection);
        staffFragment = StaffFragment.newInstance(currentUser, currentSection);

        fTrans = getFragmentManager().beginTransaction();
        fTrans.replace(R.id.frgmCont, tasksFragment);
        fTrans.commit();

        taskText = (TextView) findViewById(R.id.taskTextView);
        staffText  = (TextView) findViewById(R.id.staffTextView);
        btnStaff = (Button) findViewById(R.id.btnStaff);
        btnTasks = (Button) findViewById(R.id.btnTasks);
        btnTasks.setVisibility(View.INVISIBLE);


        if(!currentUser.hasPermission("list users")){
            staffText.setVisibility(View.GONE);
            btnStaff.setVisibility(View.GONE);
            btnTasks.setVisibility(View.GONE);
            taskText.setGravity(Gravity.CENTER_HORIZONTAL);
        }
}

    public void onClick(View v){
        fTrans = getFragmentManager().beginTransaction();
        switch (v.getId()) {
            case R.id.btnTasks:
                fTrans.remove(staffFragment);
                fTrans.add(R.id.frgmCont, tasksFragment);
                btnTasks.setVisibility(View.INVISIBLE);
                btnStaff.setVisibility(View.VISIBLE);
                break;
            case R.id.btnStaff:
                fTrans.remove(tasksFragment);
                fTrans.add(R.id.frgmCont, staffFragment);
                btnStaff.setVisibility(View.INVISIBLE);
                btnTasks.setVisibility(View.VISIBLE);
                break;
            default:
                fTrans = getFragmentManager().beginTransaction();
                fTrans.replace(R.id.frgmCont, tasksFragment);
                fTrans.commit();
                break;
        }
        fTrans.commit();
    }

    @Override
    public void  onResume(){
        super.onResume();
        Intent intent = getIntent();
        Log.d(TAG, TAG + "Status onResume");

        if (AppStatusService.wasInBackground) {
            AppStatusService.wasInBackground = false;
        }
        AppStatusService.goneToForeground = System.currentTimeMillis();

        if(AppStatusService.goneToBackground != 0) {
            AppStatusService.timeElapsed = (AppStatusService.goneToForeground -
                    AppStatusService.goneToBackground) / 1000;
        }

        if( AppStatusService.timeElapsed > 10){

            Intent currentIntent = getIntent();
            User currentUser = (User) currentIntent.getSerializableExtra("currentUser");

            Intent newIntent = new Intent();
            newIntent.putExtra("currentUser", currentUser);
            newIntent.setClass(this, VerifyPinActivity.class);
            startActivity(newIntent);
        }

        if(intent.getBooleanExtra("recreate", false) == true){
            this.recreate();
            intent.putExtra("recreate", false);
        }
    }

    @Override
    public void onPause(){
        super.onPause();
        AppStatusService.goneToBackground = System.currentTimeMillis();
    }

    @Override
    public void onBackPressed() {

        if(drawerResult.isDrawerOpen()){
            drawerResult.closeDrawer();
        }else{
            super.onBackPressed();
        }
    }
}
