package com.example.myfirstapp.Activities;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.example.myfirstapp.Services.SessionService;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.FirebaseMessaging;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import java.util.ArrayList;
import java.util.List;

public class SetPinActivity extends AppCompatActivity implements View.OnClickListener {

    private EditText PINEditText;
    private Button confirm;
    SessionService session;
    private static final String TAG = SetPinActivity.class.getName();

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_set_pin);

        PINEditText = (EditText) findViewById(R.id.pinBox);
        confirm = (Button) findViewById(R.id.confirmButton);
        confirm.setOnClickListener(this);
    }

    public void onClick(View v) {

        String givenPIN = PINEditText.getText().toString();

        if (givenPIN != "") {

            // Read the shared preference value
            session = new SessionService(getApplicationContext());
            Intent intent = getIntent();
            User currentUser = (User) intent.getSerializableExtra("currentUser");

            List<NameValuePair> body = new ArrayList<NameValuePair>();
            body.add(new BasicNameValuePair("id", Integer.toString(currentUser.getId())));
            body.add(new BasicNameValuePair("mobile_pin", givenPIN));
            body.add(new BasicNameValuePair("role_id", Integer.toString(currentUser.getRoleId())));

            Request request = new Request(currentUser.getToken(), "user/setpin", body);

            HttpService httpService = new HttpService();
            String response = httpService.sendRequest(request);

            if(response.contains("success")){
                currentUser.setPIN(givenPIN);
                body.clear();
                body.add(new BasicNameValuePair("id", Integer.toString(currentUser.getId())));
                body.add(new BasicNameValuePair("role_id", Integer.toString(currentUser.getRoleId())));
                body.add(new BasicNameValuePair("device_id", intent.getStringExtra("deviceId")));

                request.update(currentUser.getToken(), "user/savedevice", body);
                response = httpService.sendRequest(request);

                    Intent myIntent = new Intent(this, MainMenuActivity.class);
                    myIntent.putExtra("currentUser", currentUser);
                    SetPinActivity.this.startActivity(myIntent);
                    FirebaseMessaging.getInstance().subscribeToTopic("SZZ");
                    FirebaseInstanceId.getInstance().getToken();
                    session.createLoginSession(currentUser);

            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "Wystąpil bląd", Toast.LENGTH_SHORT);
                toast.show();
                Intent newIntent = new Intent();
                newIntent.setClass(this, LoginActivity.class);
                newIntent.putExtra("currentUser", currentUser);
                newIntent.putExtra("logout", true);
                SetPinActivity.this.startActivity(newIntent);
            }
        }
    }

    @Override
    public void onBackPressed() {
        moveTaskToBack(true);
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
    }

    @Override
    public void onPause(){
        super.onPause();
        AppStatusService.goneToBackground = System.currentTimeMillis();
    }
}
