package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.example.myfirstapp.Services.SessionService;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class ProfileDetailsActivity extends BasicActivity implements View.OnClickListener {

    private TextView firstNameTextView;
    private TextView surnameTextView;
    private TextView emailTextView;
    private TextView phoneTextView;
    private TextView departmentTextView;
    SessionService session;
    private Button editProfileButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_profile_details, null, false);
        drawer_layout.addView(contentView, 0);

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        User currentUser = User.deSerialize(serializedUser);


        firstNameTextView = (TextView) findViewById(R.id.firstName);
        surnameTextView = (TextView) findViewById(R.id.surname);
        emailTextView = (TextView) findViewById(R.id.email);
        phoneTextView = (TextView) findViewById(R.id.phone);
        departmentTextView = (TextView) findViewById(R.id.department);

        if(currentUser.getSectionId() != 0){
            //Building body
            HttpService httpService = new HttpService();
            BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentUser.getSectionId()));
            List<NameValuePair> sectionBody = new ArrayList<NameValuePair>();
            sectionBody.add(sectionIdBasicNameValuePair);

            //Sending request
            Request sectionRequest = new Request(currentUser.getToken(), "section/get", sectionBody);
            //Getting response
            String sectionResponse = httpService.sendRequest(sectionRequest);

            try{
                JSONObject obj = new JSONObject(sectionResponse);
                currentUser.setSectionName(obj.getJSONObject("success").getString("name"));
            }catch(Exception jex){
                jex.printStackTrace();
            }
            departmentTextView.setText(currentUser.getSectionName());
        }

        firstNameTextView.setText(currentUser.getFirstName());
        surnameTextView.setText(currentUser.getSurname());
        emailTextView.setText(currentUser.getEmail());
        phoneTextView.setText(currentUser.getPhoneNumber());

        editProfileButton = (Button) findViewById(R.id.editButton);
        editProfileButton.setOnClickListener(this);

    }

    public void onClick(View v){

        String serializedUser = session.getPref().getString(KEY_USER,"");
        User currentUser = User.deSerialize(serializedUser);

        if(v.getId() == R.id.editButton){

            Intent editIntent = new Intent();
            editIntent.setClass(this, EditProfileActivity.class);
            editIntent.putExtra("id", currentUser.getId());
            startActivity(editIntent);
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
