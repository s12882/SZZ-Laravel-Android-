package com.example.myfirstapp.Activities;

/**
 * Created by Андрей on 11.10.2017.
 */

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
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
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class LoginActivity extends Activity implements View.OnClickListener {

    private EditText usernameEditText, passwordEditText;
    private Button sendPostReqButton, clearButton;
    private CheckBox rememberMeBox;
    private boolean rememberMe;
    SessionService session;

    private  SharedPreferences loginPreferences;
    private  SharedPreferences.Editor loginPrefsEditor;

    public static String PIN = "";
    public static final String EXTRA_MESSAGE = "com.example.myfirstapp.MESSAGE";
    private static final String TAG = LoginActivity.class.getName();

    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        Log.d(TAG, TAG + " Status onCreate");
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Session Manager
        session = new SessionService(getApplicationContext());

        usernameEditText = (EditText) findViewById(R.id.loginBox);
        passwordEditText = (EditText) findViewById(R.id.passwordBox);
        rememberMeBox = (CheckBox) findViewById(R.id.rmbBox);

        loginPreferences = session.getPref();
        loginPrefsEditor = session.getEditor();

        sendPostReqButton = (Button) findViewById(R.id.loginButton);
        sendPostReqButton.setOnClickListener(this);

        clearButton = (Button) findViewById(R.id.clrButton);
        clearButton.setOnClickListener(this);

        rememberMe = loginPreferences.getBoolean("REMEMBER_ME", false);

        Intent currentIntent = getIntent();

            if (rememberMe == true) {
                usernameEditText.setText(loginPreferences.getString("KEY_LOGIN", ""));
                passwordEditText.setText(loginPreferences.getString("KEY_PASS", ""));
                rememberMeBox.setChecked(true);
                sendPostReqButton.performClick();
            }else{
                usernameEditText.setText(loginPreferences.getString("KEY_LOGIN", ""));
                passwordEditText.setText(loginPreferences.getString("KEY_PASS", ""));
            }
//        }
    }

    //** Called when Activity goes to background
    @Override
    public void onPause(){
        Log.d(TAG, TAG + "Status onPause");
        super.onPause();
        AppStatusService.goneToBackground = System.currentTimeMillis();
    }

    //** Called when Activity goes to foreground
    @Override
    public void onResume(){
        super.onResume();
        Log.d(TAG, TAG + "Status onResume");

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
    public void onBackPressed() {
        moveTaskToBack(true);
    }

    /** Called on login/clear button clicked. */
    @Override
    public void onClick(View v) {
        Log.d(TAG, TAG + "Status onLoginClick");

        if(v.getId() == R.id.clrButton){
            usernameEditText.setText("");
            passwordEditText.setText("");
            passwordEditText.setCursorVisible(false);
            passwordEditText.setFocusable(false);
            usernameEditText.setCursorVisible(true);
            passwordEditText.setFocusable(true);
            rememberMeBox.setChecked(false);
        }else if(v.getId() == R.id.loginButton) {
            String givenUsername = usernameEditText.getEditableText().toString();
            String givenPassword = passwordEditText.getEditableText().toString();

            if (givenUsername != "" || givenPassword != ""){

                //Building body
                BasicNameValuePair usernameBasicNameValuePair = new BasicNameValuePair("login", givenUsername);
                BasicNameValuePair passwordBasicNameValuePAir = new BasicNameValuePair("password", givenPassword);

                List<NameValuePair> body = new ArrayList<NameValuePair>();
                body.add(usernameBasicNameValuePair);
                body.add(passwordBasicNameValuePAir);
                Request request = new Request("empty", "login", body);

                //Sending request
                HttpService httpService = new HttpService();
                String response = httpService.sendRequest(request);
                User currentUser = new User();

                //Parsing JSON
                try {
                    JSONObject jObj = new JSONObject(response);
                    String token = jObj.getString("token");
                    currentUser.setToken(token);

                } catch (Exception ioe) {
                    System.out.println("Response reading error... " + ioe);
                    ioe.printStackTrace();
                }

                //**If returns token
                if(currentUser.getToken() != null) {

                    if (!currentUser.getToken().isEmpty()) {

                        Request requestDetails = new Request(currentUser.getToken(), "details");
                        String responseDetails = httpService.sendRequest(requestDetails);

                        try {
                            JSONObject obj = new JSONObject(responseDetails);

                            //**Pobieramy info o użytkoniku z respons`a
                            currentUser.setUsername(givenUsername);
                            currentUser.setPassword(givenPassword);
                            currentUser.setId(obj.getJSONObject("success").getInt("id"));
                            currentUser.setLogin(obj.getJSONObject("success").getString("login"));
                            currentUser.setFirstName(obj.getJSONObject("success").getString("first_name"));
                            currentUser.setSurname(obj.getJSONObject("success").getString("surname"));
                            currentUser.setPhoneNumber(obj.getJSONObject("success").getString("phoneNumber"));
                            currentUser.setEmail(obj.getJSONObject("success").getString("email"));
                            currentUser.loadTasks();


                            if(obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getString("id") != "null"){
                                currentUser.setRole(Integer.parseInt(obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getString("id")));
                                currentUser.loadRole();
                            }else{
                                currentUser.loadRole();
                            }

                            if(obj.getJSONObject("success").getString("section_id") != "null"){
                                currentUser.setSectionId(obj.getJSONObject("success").getInt("section_id"));
                            }else{
                                currentUser.setSectionId(1);
                            }
                            currentUser.loadSection();

                            //**Pobieramy i ustawiamy uprawnienia
                            JSONArray jPermissions = obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getJSONArray("permissions");
                            ArrayList<String> permissions = new ArrayList<String>();
                            if (jPermissions != null) {
                                for (int i = 0; i < jPermissions.length(); i++) {
                                    permissions.add(jPermissions.getJSONObject(i).getString("name"));
                                }
                            }
                            currentUser.setPermissions(permissions);

                            //**Funkcja Remember Me
                            if (rememberMeBox.isChecked()) { //** Jezeli wlącząna
                                loginPrefsEditor.putBoolean("REMEMBER_ME", true);
                                loginPrefsEditor.putString("KEY_LOGIN", givenUsername);
                                loginPrefsEditor.putString("KEY_PASS", givenPassword);
                                loginPrefsEditor.commit();
                            } else { //** Jeżeli nie
                                loginPrefsEditor.clear();
                                loginPrefsEditor.commit();
                            }
                                //**Sprawdzamy czy jest PIN
                                String jPin = obj.getJSONObject("success").getString("mobile_pin");
                                if(jPin == "null"){ //**Jeżeli nie to ustawiamy go
                                    Intent newIntent = new Intent();
                                    newIntent.setClass(this, SetPinActivity.class);
                                    newIntent.putExtra("currentUser", currentUser);
                                    newIntent.putExtra("deviceId", FirebaseInstanceId.getInstance().getToken());
                                    LoginActivity.this.startActivity(newIntent);
                                }else{ //**Jezeli pin już istnieje to logujemy
                                    currentUser.setPIN(jPin);
                                    Intent myIntent = new Intent(this, MainMenuActivity.class);
                                    myIntent.putExtra("currentUser", currentUser);
                                    LoginActivity.this.startActivity(myIntent);
                                    FirebaseMessaging.getInstance().subscribeToTopic("SZZ");
                                    FirebaseInstanceId.getInstance().getToken();
                                    session.createLoginSession(currentUser);
                                }
                        } catch (Exception ioe) {
                            Toast toast = Toast.makeText(getApplicationContext(), "Bląd podczas logowania", Toast.LENGTH_SHORT);
                            toast.show();
                        }
                    }else{
                        Toast toast = Toast.makeText(getApplicationContext(), "Bląd podczas logowania", Toast.LENGTH_SHORT);
                        toast.show();
                    }
                }
            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "Nie podane dane", Toast.LENGTH_SHORT);
                toast.show();
            }
        }
    }

}
