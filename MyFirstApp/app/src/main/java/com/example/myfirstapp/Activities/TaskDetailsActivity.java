package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.Task;
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

public class TaskDetailsActivity extends BasicActivity implements View.OnClickListener {

    private TextView taskName, taskDetails, taskLocation;
    private Button takeTask, addItem, deleteButton, editButton;
    private Task currentTask;
    private Section currentSection;
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_task_details, null, false);
        drawer_layout.addView(contentView, 0);

        Intent intent = getIntent();

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER, "");
        User currentUser = User.deSerialize(serializedUser);
        currentSection = (Section)intent.getSerializableExtra("currentSection");
        currentTask = (Task)intent.getSerializableExtra("currentTask");

        //Building body
        BasicNameValuePair employeeIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentTask.getId()));
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(employeeIdBasicNameValuePair);

        //Sending request
        Request request = new Request(currentUser.getToken(), "task/get", body);
        HttpService httpService = new HttpService();
        //Getting response
        String response = httpService.sendRequest(request);
        currentTask = new Task();

        try {
            JSONObject obj = new JSONObject(response);
            currentTask.setId(obj.getJSONObject("success").getInt("id"));
            currentTask.setName(obj.getJSONObject("success").getString("name"));
            currentTask.setDescription(obj.getJSONObject("success").getString("description"));
            currentTask.setLocation(obj.getJSONObject("success").getString("location"));
            currentTask.setSection_id(obj.getJSONObject("success").getInt("section_id"));
        } catch (Exception jre) {
            System.out.println("Response reading error... " + jre);
            jre.printStackTrace();
        }

        taskName = (TextView) findViewById(R.id.taskName);
        taskDetails = (TextView) findViewById(R.id.taskDetails);
        taskLocation = (TextView) findViewById(R.id.taskLocation);

        takeTask = (Button) findViewById(R.id.takeTask);
        editButton = (Button) findViewById(R.id.editButton);
        deleteButton = (Button) findViewById(R.id.deleteButton);

        if(currentUser.hasTask(currentTask)){
            takeTask.setVisibility(View.GONE);
        }else{
            takeTask.setOnClickListener(this);
        }


        if(currentUser.hasPermission("delete task")){
            deleteButton.setOnClickListener(this);
        }else{
            deleteButton.setVisibility(View.GONE);
        }

        if(currentUser.hasPermission("update task")){
            editButton.setOnClickListener(this);
        }else{
            editButton.setVisibility(View.GONE);
        }

        taskName.setText(currentTask.getName());
        taskDetails.setText(currentTask.getDescription());
        taskLocation.setText(currentTask.getLocation());
    }

    public void onClick(View v) {

        String serializedUser = session.getPref().getString(KEY_USER, "");
        User currentUser = User.deSerialize(serializedUser);

        Intent intent = getIntent();

        if(v.getId() == R.id.deleteButton){
            Intent deleteIntent = new Intent();
            deleteIntent.setClass(this, DeleteTaskActivity.class);
            deleteIntent.putExtra("currentTask", currentTask);
            deleteIntent.putExtra("currentSection", currentSection);
            startActivity(deleteIntent);
        }

        if(v.getId() == R.id.editButton){
            Intent deleteIntent = new Intent();
            deleteIntent.setClass(this, EditTaskActivity.class);
            deleteIntent.putExtra("currentTask", currentTask);
            deleteIntent.putExtra("currentSection", currentSection);
            startActivity(deleteIntent);
        }

        if (v.getId() == R.id.takeTask) {
            //Building body
            BasicNameValuePair taskIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentTask.getId()));
            BasicNameValuePair userIdBasicNameValuePair = new BasicNameValuePair("user_id", Integer.toString(currentUser.getId()));
            List<NameValuePair> body = new ArrayList<NameValuePair>();
            body.add(taskIdBasicNameValuePair);
            body.add(userIdBasicNameValuePair);

            //Sending request
            Request request = new Request(currentUser.getToken(), "task/reserve", body);
            HttpService httpService = new HttpService();
            String response = httpService.sendRequest(request);

            if(response.contains("success")){
                currentUser.assignTask(currentTask);
                takeTask.setVisibility(View.GONE);
                session.updateSession(currentUser);
                Toast toast = Toast.makeText(getApplicationContext(), "Zostales przypisany do zadania", Toast.LENGTH_SHORT);
                toast.show();
            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "Wystąpil bląd", Toast.LENGTH_SHORT);
                toast.show();
            }
        }

        //TODO
//        if (v.getId() == R.id.addItem) {
//
//        }
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
