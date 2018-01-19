package com.example.myfirstapp.Activities;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
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

import java.util.ArrayList;
import java.util.List;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class DeleteTaskActivity extends AppCompatActivity implements View.OnClickListener {

    private Button confirm, deny;
    private Task currentTask;
    Section currentSection;
    SessionService session;
    User currentUser;
    int duration = Toast.LENGTH_SHORT;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_delete_task);

        Intent intent = getIntent();

        session = new SessionService(getApplicationContext());
        currentSection = (Section)intent.getSerializableExtra("currentSection");
        setTitle("Usunąć zadanie?");
        confirm = (Button) findViewById(R.id.confirmButton);
        confirm.setOnClickListener(this);

        deny = (Button) findViewById(R.id.denyButton);
        deny.setOnClickListener(this);
    }

    public void onClick(View v){

        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);

        if(v.getId() == R.id.confirmButton){
            Intent currentIntent = getIntent();
            currentTask = (Task)currentIntent.getSerializableExtra("currentTask");

            //Building body
            BasicNameValuePair taskIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(currentTask.getId()));
            List<NameValuePair> body = new ArrayList<NameValuePair>();
            body.add(taskIdBasicNameValuePair);

            //Sending request
            Request request = new Request(currentUser.getToken(), "task/destroy", body);
            HttpService httpService = new HttpService();
            //Getting response
            String response = httpService.sendRequest(request);

            Intent sectionIntent = new Intent(this, SectionActivity.class);
            sectionIntent.putExtra("currentUser", currentUser);
            sectionIntent.putExtra("currentSection", currentSection);
            sectionIntent.putExtra("recreate", true);
            DeleteTaskActivity.this.startActivity(sectionIntent);

            if(response.contains("success")){
                Toast toast = Toast.makeText(getApplicationContext(), "Zadanie zostalo usunięte", duration);
                toast.show();
            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "Bład", duration);
                toast.show();
            }
        }

        if(v.getId() == R.id.denyButton){
            finish();
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

            session = new SessionService(getApplicationContext());
            String serializedUser = session.getPref().getString(KEY_USER,"");
            User currentUser = User.deSerialize(serializedUser);

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
