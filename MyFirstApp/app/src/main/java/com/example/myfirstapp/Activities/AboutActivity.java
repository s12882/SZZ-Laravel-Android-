package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import com.example.myfirstapp.Fragments.AboutFragment;
import com.example.myfirstapp.Fragments.ContactsFragment;
import com.example.myfirstapp.Fragments.StaffFragment;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;

import android.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;


public class AboutActivity extends BasicActivity {

    AboutFragment about;
    ContactsFragment contacts;
    StaffFragment staffFragment;
    FragmentTransaction fTrans;
    Button btAbout, btContacts;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_fragments, null, false);
        drawer_layout.addView(contentView, 0);

        Intent intent = getIntent();
        User currentUser = (User) intent.getSerializableExtra("currentUser");

        about = new AboutFragment();
        contacts = new ContactsFragment();
        staffFragment = new StaffFragment();

        btAbout = (Button) findViewById(R.id.btnAbout);
        btContacts = (Button) findViewById(R.id.btnContacts);
        btAbout.setVisibility(View.INVISIBLE);

        fTrans = getFragmentManager().beginTransaction();
        fTrans.replace(R.id.frgmCont, about);
        fTrans.commit();
    }

    public void onClick(View v){
        fTrans = getFragmentManager().beginTransaction();
        switch (v.getId()) {
            case R.id.btnContacts:
                    fTrans.remove(about);
                    fTrans.add(R.id.frgmCont, contacts);
                    btContacts.setVisibility(View.INVISIBLE);
                    btAbout.setVisibility(View.VISIBLE);
                    break;
            case R.id.btnAbout:
                    fTrans.remove(contacts);
                    fTrans.add(R.id.frgmCont, about);
                    btAbout.setVisibility(View.INVISIBLE);
                    btContacts.setVisibility(View.VISIBLE);
                    break;
            default:
                break;
        }
        fTrans.commit();
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
