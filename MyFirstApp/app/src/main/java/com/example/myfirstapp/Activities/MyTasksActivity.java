package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ListView;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;

import com.example.myfirstapp.Models.Task;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.SessionService;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class MyTasksActivity extends BasicActivity implements View.OnClickListener, AdapterView.OnItemClickListener {

    private User currentUser;
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_my_tasks, null, false);
        drawer_layout.addView(contentView, 0);
        setTitle("Moje Zadania" );

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);

        ListView lvTasks = (ListView) findViewById(R.id.tasks);
        lvTasks.setOnItemClickListener(this);

        ArrayAdapter<Task> adapter = new ArrayAdapter<>(this,
                android.R.layout.simple_list_item_1, currentUser.getUserTasks());
        lvTasks.setAdapter(adapter);
    }

    @Override
    public void onItemClick(AdapterView<?> parent, View view, int position, long id) {

        Task task = (Task)parent.getItemAtPosition(position);
        id = task.getId();

        Intent newIntent = new Intent();
        newIntent.setClass(this, TaskDetailsActivity.class);
        newIntent.putExtra("currentUser", currentUser);
        newIntent.putExtra("currentSection", currentUser.getSection());
        newIntent.putExtra("currentTask", task);
        newIntent.putExtra("id", id);
        startActivity(newIntent);
    }

    @Override
    public void onClick(View v) {

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
