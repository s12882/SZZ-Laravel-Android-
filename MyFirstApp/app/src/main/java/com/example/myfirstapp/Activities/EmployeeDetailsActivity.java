package com.example.myfirstapp.Activities;

/**
 * Created by Андрей on 12.11.2017.
 */

import android.Manifest;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.example.myfirstapp.Models.Employee;
import com.example.myfirstapp.Models.Section;
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

public class EmployeeDetailsActivity extends BasicActivity implements View.OnClickListener {

    private TextView firstnameTextView;
    private TextView surnameTextView;
    private TextView emailTextView;
    private TextView phoneTextView;
    private TextView departmentTextView;
    private Employee currentEmployee;
    Section currentSection;
    User currentUser;
    SessionService session;

    private Button editUserButton, deleteUserButton, callButton, mailButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_employee_details, null, false);
        drawer_layout.addView(contentView, 0);

        Intent intent = getIntent();

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);
        currentEmployee = (Employee) intent.getSerializableExtra("currentEmployee");;
        currentSection = (Section)intent.getSerializableExtra("currentSection");
        int employee_id = currentEmployee.getId();

        //Building body
            BasicNameValuePair employeeIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentEmployee.getId()));
            List<NameValuePair> body = new ArrayList<NameValuePair>();
            body.add(employeeIdBasicNameValuePair);

        //Sending request
            Request request = new Request(currentUser.getToken(), "user/profile", body);
            HttpService httpService = new HttpService();
        //Getting response
            String response = httpService.sendRequest(request);
            currentEmployee = new Employee();

        //Parsing JSON
        try{
            JSONObject obj = new JSONObject(response);
            currentEmployee.setId(employee_id);
            currentEmployee.setName(obj.getJSONObject("success").getString("first_name"));
            currentEmployee.setEmail(obj.getJSONObject("success").getString("email"));
            currentEmployee.setSurname(obj.getJSONObject("success").getString("surname"));
            currentEmployee.setLogin(obj.getJSONObject("success").getString("login"));
            currentEmployee.setPhoneNumber(obj.getJSONObject("success").getString("phoneNumber"));
            currentEmployee.setSection_id(obj.getJSONObject("success").getInt("section_id"));
            currentEmployee.setRole_id((obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getString("")));
        }catch(Exception ioe){
            System.out.println("Response reading error... " + ioe);
            ioe.printStackTrace();
        }

        //-----------GETTING SECTION NAME------------//
        //Building body
        BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentEmployee.getSection_id()));
        List<NameValuePair> sectionBody = new ArrayList<NameValuePair>();
        sectionBody.add(sectionIdBasicNameValuePair);

        //Sending request
        Request sectionRequest = new Request(currentUser.getToken(), "section/get", sectionBody);
        //Getting response
        String sectionResponse = httpService.sendRequest(sectionRequest);

        try{
            JSONObject obj = new JSONObject(sectionResponse);
            currentEmployee.setSectionName(obj.getJSONObject("success").getString("name"));
        }catch(Exception jex){
            jex.printStackTrace();
        }

        firstnameTextView = (TextView) findViewById(R.id.firstName);
        surnameTextView = (TextView) findViewById(R.id.surname);
        emailTextView = (TextView) findViewById(R.id.email);
        phoneTextView = (TextView) findViewById(R.id.phone);
        departmentTextView = (TextView) findViewById(R.id.department);

        firstnameTextView.setText(currentEmployee.getName());
        surnameTextView.setText(currentEmployee.getSurname());
        emailTextView.setText(currentEmployee.getEmail());
        phoneTextView.setText(currentEmployee.getPhoneNumber());
        departmentTextView.setText(currentEmployee.getSectionName());

        editUserButton = (Button) findViewById(R.id.editButton);
        if(currentUser.getPermissions().contains("update user")){

            editUserButton.setOnClickListener(this);
        }else{
            editUserButton.setVisibility(View.GONE);
        }

        System.out.println("currentUser " + currentUser.getId() + "currentEmployee" + currentEmployee.getId());

        deleteUserButton = (Button) findViewById(R.id.deleteButton);
        if(currentUser.getPermissions().contains("delete user") && currentUser.getId() != currentEmployee.getId()){
            deleteUserButton.setOnClickListener(this);
        }else{
            deleteUserButton.setVisibility(View.GONE);
        }
            callButton = (Button) findViewById(R.id.callButton);
            callButton.setOnClickListener(this);

            mailButton = (Button) findViewById(R.id.mailButton);
            mailButton.setOnClickListener(this);
    }

    public void onClick(View v){

        if(v.getId() == R.id.deleteButton){

            Intent deleteIntent = new Intent();
            deleteIntent.setClass(this, DeleteUserActivity.class);
            deleteIntent.putExtra("currentEmployee", currentEmployee);
            deleteIntent.putExtra("currentSection", currentSection);
            startActivity(deleteIntent);
        }

        if(v.getId() == R.id.editButton){

            Intent editIntent = new Intent();
            editIntent.setClass(this, EditUserActivity.class);
            editIntent.putExtra("currentEmployee", currentEmployee);
            editIntent.putExtra("currentSection", currentSection);
            startActivity(editIntent);
        }

        if(v.getId() == R.id.callButton){
            Intent callIntent = new Intent(Intent.ACTION_CALL);
            callIntent.setData(Uri.parse("tel:" + phoneTextView.getText()));

            if (ActivityCompat.checkSelfPermission(this,
                    Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                Toast toast = Toast.makeText(getApplicationContext(), "Aplikacja nie ma zezwolenia na telefon", Toast.LENGTH_SHORT);
                toast.show();
                return;
            }
            startActivity(callIntent);
        }

        if(v.getId() == R.id.mailButton){

            Intent sendIntent = new Intent(Intent.ACTION_VIEW);
            sendIntent.setType("plain/text");
            sendIntent.setData(Uri.parse("" + emailTextView.getText()));
            sendIntent.setClassName("com.google.android.gm", "com.google.android.gm.ComposeActivityGmail");
            sendIntent.putExtra(Intent.EXTRA_EMAIL, new String[] { "" + emailTextView.getText() });
            sendIntent.putExtra(Intent.EXTRA_SUBJECT, "");
            startActivity(sendIntent);
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
