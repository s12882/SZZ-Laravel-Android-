package com.example.myfirstapp.Activities;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.ListView;
import android.widget.TextView;

import com.example.myfirstapp.Models.Employee;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.SessionService;

import java.util.ArrayList;

public class AddEmployeesActivity extends BasicActivity implements View.OnClickListener {

    private ListView lv;
    private ArrayList<Employee> selectedEmployees = new ArrayList<Employee>();
    private ArrayList<Employee> employeesList = new ArrayList<Employee>();

    private Button setEmployeesButton;
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_employees);

        lv = (ListView) findViewById(R.id.employeesList);
        CustomAdapter adapter = new CustomAdapter(this, CreateTaskActivity.Employees);
        lv.setAdapter(adapter);

        setEmployeesButton = (Button) findViewById(R.id.setEmployeesButton);
        setEmployeesButton.setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
            finish();
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


    class CustomAdapter extends ArrayAdapter<Employee> {

        ArrayList<Employee> modelItems = null;
        Context context;
        int position;

        public CustomAdapter(Context context, ArrayList<Employee> employees) {
            super(context, R.layout.employees_row, employees);

            this.context = context;
            this.modelItems = employees;
        }

        @Override
        public View getView(final int position, View convertView, ViewGroup parent) {

            LayoutInflater inflater = ((Activity) context).getLayoutInflater();
            convertView = inflater.inflate(R.layout.employees_row, parent, false);
            TextView name = (TextView) convertView.findViewById(R.id.textView1);
            CheckBox cb = (CheckBox) convertView.findViewById(R.id.checkBox1);

            if(modelItems.get(position).isSelected())
                cb.setChecked(true);

            cb.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {

                    if(isChecked){
                        modelItems.get(position).setChecked();
                    }else {
                        modelItems.get(position).setUnChecked();
                    }
                }
            });


            name.setText(modelItems.get(position).getName()+ " " + modelItems.get(position).getSurname());
            CreateTaskActivity.Employees = modelItems;
            return convertView;
        }
    }

}


