package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;

import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.SessionService;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class MainMenuActivity extends BasicActivity implements View.OnClickListener {

    private Button taskBtn, staffBtn, sectionsBtn, profileBtn;
    User currentUser;
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_main_menu, null, false);
        drawer_layout.addView(contentView, 0);

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);

        taskBtn = (Button) findViewById(R.id.taskBtn);
        taskBtn.setOnClickListener(this);


        staffBtn = (Button) findViewById(R.id.staffBtn);
        if(currentUser.hasPermission("list users")) {
            staffBtn.setOnClickListener(this);
        }else{
            staffBtn.setVisibility(View.GONE);
        }

        sectionsBtn = (Button) findViewById(R.id.sectionsBtn);
        if(currentUser.hasPermission("list sections")) {
            sectionsBtn.setOnClickListener(this);
        }else{
            sectionsBtn.setVisibility(View.GONE);
        }

        profileBtn = (Button) findViewById(R.id.profileBtn);
        profileBtn.setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {

        if (v.getId() == R.id.taskBtn) {
            Intent sectionIntent = new Intent(MainMenuActivity.this, SectionHomeActivity.class);
            sectionIntent.putExtra("currentUser", currentUser);
            startActivity(sectionIntent);
        }

        if (v.getId() == R.id.staffBtn) {
            Intent sectionIntent = new Intent(MainMenuActivity.this, SectionHomeActivity.class);
            sectionIntent.putExtra("currentUser", currentUser);
            sectionIntent.putExtra("FRAGMENT", "STAFF");
            startActivity(sectionIntent);
        }

        if (v.getId() == R.id.sectionsBtn) {
            Intent sectionsIntent = new Intent(MainMenuActivity.this, SectionsActivity.class);
            sectionsIntent.putExtra("currentUser", currentUser);
            startActivity(sectionsIntent);
        }

        if (v.getId() == R.id.profileBtn) {
            Intent profileIntent = new Intent(MainMenuActivity.this, ProfileDetailsActivity.class);
            profileIntent.putExtra("currentUser", currentUser);
            startActivity(profileIntent);
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
