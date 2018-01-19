package com.example.myfirstapp.Activities;

/**
 * Created by Андрей on 06.11.2017.
 */

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Layout;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.SessionService;
import com.mikepenz.iconics.typeface.FontAwesome;
import com.mikepenz.materialdrawer.Drawer;
import com.mikepenz.materialdrawer.model.PrimaryDrawerItem;
import com.mikepenz.materialdrawer.model.SecondaryDrawerItem;
import com.mikepenz.materialdrawer.model.SectionDrawerItem;
import com.mikepenz.materialdrawer.model.interfaces.IDrawerItem;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class BasicActivity extends AppCompatActivity {

    private Drawer drawer;
    private ImageView headerImage;
    private Layout header;
    protected Drawer.Result drawerResult;
    protected Toolbar toolbar;
    protected FrameLayout frame;
    protected DrawerLayout drawer_layout;

    // Session Manager Class
    SessionService session;
    User currentUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_basic);

        // Read the shared preference value
        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);

        //Inicjalizacja toolbar
        drawer_layout =  (DrawerLayout)findViewById(R.id.drawer_layout);
        frame = (FrameLayout) findViewById(R.id.content_frame);
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        // Inicjalizacja Navigation Drawer
        drawer = new Drawer();
        drawer.withActivity(this);
        drawer.withToolbar(toolbar);
        drawer.withActionBarDrawerToggle(true);
        drawer.withHeader(R.layout.drawer_header);

        if(currentUser.hasPermission("list sections")) {
            drawer.addDrawerItems(new PrimaryDrawerItem().withName(R.string.drawer_item_sections).withIcon(FontAwesome.Icon.faw_list).withIdentifier(1));
        }

        if(currentUser.getSectionId() != 0) {
            drawer.addDrawerItems(new PrimaryDrawerItem().withName(R.string.drawer_item_section_staff).withIcon(FontAwesome.Icon.faw_home).withIdentifier(2));
        }

        if(currentUser.getSectionId() != 0) {
            drawer.addDrawerItems(new PrimaryDrawerItem().withName(R.string.drawer_item_myTasks).withIcon(FontAwesome.Icon.faw_list_alt).withIdentifier(4));
        }

        drawer.addDrawerItems(new PrimaryDrawerItem().withName(R.string.frags_bar).withIcon(FontAwesome.Icon.faw_question).withIdentifier(5));
        drawer.addDrawerItems(new SectionDrawerItem().withName(R.string.drawer_item_divider));
        drawer.addDrawerItems(new PrimaryDrawerItem().withName(R.string.drawer_item_logout).withIcon(FontAwesome.Icon.faw_user_md).withIdentifier(6));

        drawer.withOnDrawerListener(new Drawer.OnDrawerListener() {
            @Override
            public void onDrawerOpened(View drawerView) {
                // Ukrywanie klawiatury jezeli sidebar otwarty
                InputMethodManager inputMethodManager = (InputMethodManager) BasicActivity.this.getSystemService(Activity.INPUT_METHOD_SERVICE);
                inputMethodManager.hideSoftInputFromWindow(BasicActivity.this.getCurrentFocus().getWindowToken(), 0);
            }

            @Override
            public void onDrawerClosed(View drawerView) {
            }
        });
        drawer.withOnDrawerItemClickListener(new Drawer.OnDrawerItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id, IDrawerItem drawerItem) {

               try {
                   int btnId = drawerItem.getIdentifier();
                   switch (btnId) {
                       case 1:
                           Intent sectionsIntent = new Intent(BasicActivity.this, SectionsActivity.class);
                           sectionsIntent.putExtra("currentUser", currentUser);
                           startActivity(sectionsIntent);
                           break;
                       case 2:
                           Intent sectionIntent = new Intent(BasicActivity.this, SectionHomeActivity.class);
                           sectionIntent.putExtra("currentUser", currentUser);
                           startActivity(sectionIntent);
                           break;
                       case 4:
                           Intent myTasksIntent = new Intent(BasicActivity.this, MyTasksActivity.class);
                           myTasksIntent.putExtra("currentUser", currentUser);
                           startActivity(myTasksIntent);
                           break;
                       case 6:
                           session.logoutUser();
                           break;
                       case 5:
                           Intent fragmentIntent = new Intent();
                           fragmentIntent.setClass(BasicActivity.this, AboutActivity.class);
                           fragmentIntent.putExtra("currentUser", currentUser);
                           startActivity(fragmentIntent);
                           break;
                       default:
                   }
               }catch (NullPointerException NPE){
                   Intent profileIntent = new Intent(BasicActivity.this, MainMenuActivity.class);
                   profileIntent.putExtra("currentUser", currentUser);
                   startActivity(profileIntent);
               }
            }
        });

        drawer.withOnDrawerItemLongClickListener(new Drawer.OnDrawerItemLongClickListener() {
            @Override
            public boolean onItemLongClick(AdapterView<?> parent, View view, int position, long id, IDrawerItem drawerItem) {
                if (drawerItem instanceof SecondaryDrawerItem) {
                    Toast.makeText(BasicActivity.this, BasicActivity.this.getString(((SecondaryDrawerItem) drawerItem).getNameRes()), Toast.LENGTH_SHORT).show();
                }
                return false;
            }
        });

        drawerResult = drawer.build();
    }

    @Override
    public void onBackPressed(){
        if(drawerResult.isDrawerOpen()){
            drawerResult.closeDrawer();
        }
        else{
            super.onBackPressed();
        }
    }

    @Override
    public void  onResume(){
        super.onResume();

        if (AppStatusService.wasInBackground) {
            AppStatusService.wasInBackground = false;
        }
        AppStatusService.goneToForeground = System.currentTimeMillis();

        if(AppStatusService.goneToBackground != 0) {
            AppStatusService.timeElapsed = (AppStatusService.goneToForeground -
                    AppStatusService.goneToBackground) / 1000;
        }

        if( AppStatusService.timeElapsed > 240){

            Intent currentIntent = getIntent();
            User currentUser = (User) currentIntent.getSerializableExtra("currentUser");

            Intent newIntent = new Intent();
            newIntent.putExtra("currentUser", currentUser);
            newIntent.setClass(this, VerifyPinActivity.class);
            startActivity(newIntent);
        }
    }

    @Override
    public void onPause(){
        super.onPause();
        AppStatusService.goneToBackground = System.currentTimeMillis();
    }

}
