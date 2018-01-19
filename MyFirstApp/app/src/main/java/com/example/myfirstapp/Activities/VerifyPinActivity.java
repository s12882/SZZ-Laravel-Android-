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
import com.example.myfirstapp.Services.SessionService;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class VerifyPinActivity extends AppCompatActivity implements View.OnClickListener {

    private EditText PINEditText;
    private Button confirm;
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verify_pin);

        PINEditText = (EditText) findViewById(R.id.VpinBox);
        confirm = (Button) findViewById(R.id.confirmPinButton);
        confirm.setOnClickListener(this);
    }

    public void onClick(View v) {

        String givenPIN = PINEditText.getText().toString();

        if (givenPIN != "") {

            session = new SessionService(getApplicationContext());
            String serializedUser = session.getPref().getString(KEY_USER,"");
            User currentUser = User.deSerialize(serializedUser);

            if (givenPIN.equals(currentUser.getPIN())) {
                 finish();
            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "PIN nie zgadza się", Toast.LENGTH_SHORT);
                toast.show();
            }
        }else{
            Toast toast = Toast.makeText(getApplicationContext(), "Podaj PIN", Toast.LENGTH_SHORT);
            toast.show();
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
