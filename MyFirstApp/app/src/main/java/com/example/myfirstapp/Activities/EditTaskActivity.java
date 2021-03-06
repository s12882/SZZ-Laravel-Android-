package com.example.myfirstapp.Activities;

import android.app.DatePickerDialog;
import android.app.DialogFragment;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.myfirstapp.Fragments.DatePickerDialogFragment;
import com.example.myfirstapp.Models.Employee;
import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.Task;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.example.myfirstapp.Services.SessionService;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.text.Format;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class EditTaskActivity extends BasicActivity implements View.OnClickListener, AdapterView.OnItemSelectedListener, DatePickerDialog.OnDateSetListener {

    private EditText nameEditText, locationEditText, detailsEditText;
    private int year, month, day;
    private String date;
    private DatePicker datePicker;
    private Spinner sectionSpinner;
    private ArrayAdapter<String> adapter;
    private Button sendPostReqButton, addEmployeesButton, changeDate;
    public static ArrayList<Employee> Employees = new ArrayList<Employee>();
    public static ArrayList<Employee> assignedEmployees = new ArrayList<Employee>();
    private Task currentTask;
    private Section currentSection;
    SessionService session;
    User currentUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_edit_task, null, false);
        drawer_layout.addView(contentView, 0);

        Intent intent = getIntent();
        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);
        currentSection = (Section)intent.getSerializableExtra("currentSection");
        currentTask = (Task)intent.getSerializableExtra("currentTask");

        nameEditText = (EditText) findViewById(R.id.newNameBox);
        locationEditText = (EditText) findViewById(R.id.newLocationBox);
        detailsEditText = (EditText) findViewById(R.id.newDetailsBox);
        sectionSpinner = (Spinner) findViewById(R.id.newSectionSpinner);

        nameEditText.setText(currentTask.getName());
        locationEditText.setText(currentTask.getLocation());
        detailsEditText.setText(currentTask.getDescription());

        //-----------GETTING ALL SECTIONS-----------//
        ArrayList<Section> Sections = new ArrayList<Section>();
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        //Sending request
        Request request = new Request(currentUser.getToken(),"section", body);
        HttpService httpService = new HttpService();

        //Getting response
        String response = httpService.sendRequest(request);

        try {
            JSONObject obj = new JSONObject(response);
            JSONArray jSections = obj.getJSONArray("success");

            if (jSections != null) {
                for (int i = 0; i < jSections.length(); i++) {
                    Section toList = new Section(Integer.parseInt(jSections.getJSONObject(i).getString("id")),
                            jSections.getJSONObject(i).getString("name"));
                    Sections.add(toList);

                    toList = null;
                }
            }
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        //Sections dropdown list
        ArrayAdapter<Section> sectionAdapter = new ArrayAdapter<Section>(this, android.R.layout.simple_spinner_dropdown_item, Sections);
        sectionAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        sectionSpinner.setAdapter(sectionAdapter);
        sectionSpinner.setSelection(currentSection.getId()-1);

        //--------------GETTING ALL EMPLOYEES------------------//
        body.add(new BasicNameValuePair("section_id", Integer.toString(currentSection.getId())));
        request.update(currentUser.getToken(), "section/staff", body);
        //Getting response
        String usersResponse = httpService.sendRequest(request);

        try {
            JSONObject obj = new JSONObject(usersResponse);
            JSONArray jEmployees = obj.getJSONArray("success");

            if (jEmployees != null) {
                for (int i = 0; i < jEmployees.length(); i++) {
                    Employee toList = new Employee(jEmployees.getJSONObject(i).getInt("id"),
                            jEmployees.getJSONObject(i).getString("first_name"),
                            jEmployees.getJSONObject(i).getString("surname")
                    );
                    Employees.add(toList);
                    toList = null;
                }
            }
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        //--------------GETTING ASSIGNED EMPLOYEES------------------//
        body.add(new BasicNameValuePair("id", Integer.toString(currentTask.getId())));
        request.update(currentUser.getToken(), "task/users", body);
        //Getting response
        String assignedResponse = httpService.sendRequest(request);

        try {
            JSONObject obj = new JSONObject(assignedResponse);
            JSONArray jEmployees = obj.getJSONArray("success");

            if (jEmployees != null) {
                for (int i = 0; i < jEmployees.length(); i++) {
                    Employee toList = new Employee(jEmployees.getJSONObject(i).getInt("id"),
                            jEmployees.getJSONObject(i).getString("first_name"),
                            jEmployees.getJSONObject(i).getString("surname")
                    );
                    assignedEmployees.add(toList);
                    toList = null;
                }
            }
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        for(int i = 0; i < Employees.size(); i++){
            for(int j = 0; j < assignedEmployees.size(); j++){
                System.out.println("ASSIGNED: " + assignedEmployees.get(j).getName());
                if(Employees.get(i).getId() == assignedEmployees.get(j).getId()){
                    Employees.get(i).setChecked();
                }
            }
        }

        //-------DATA SET---------//
        changeDate = (Button) findViewById(R.id.changeDate);

        final Calendar c = Calendar.getInstance();
        year = c.get(Calendar.YEAR);
        month = c.get(Calendar.MONTH);
        day = c.get(Calendar.DAY_OF_MONTH);

        Format formatter = new SimpleDateFormat("yyyy-MM-dd");
        String s = formatter.format(c.getTime());

        changeDate.setText(s);

        //---------BUTTONS LISTENERS----------//
        sendPostReqButton = (Button) findViewById(R.id.createTaskButton);
        sendPostReqButton.setOnClickListener(this);

        addEmployeesButton = (Button) findViewById(R.id.addEmployeesButton);
        addEmployeesButton.setOnClickListener(this);

        changeDate = (Button) findViewById(R.id.changeDate);
        changeDate.setOnClickListener(this);

        sectionSpinner.setOnItemSelectedListener(this);
    }

    public void onClick(View v) {

        if(v.getId() == R.id.createTaskButton){

            String taskId = Integer.toString(currentTask.getId());
            String givenName = nameEditText.getEditableText().toString();
            String givenDetails = detailsEditText.getEditableText().toString();
            String givenLocations = locationEditText.getEditableText().toString();
            String givenDate = changeDate.getText().toString();

            Section section = (Section) sectionSpinner.getSelectedItem();
            int sectionId = section.getId();
            String givenSection = String.valueOf(sectionId);

            //Validation
            boolean failFlag = false;
            if(givenName.equalsIgnoreCase(""))
            {
                failFlag = true;
                nameEditText.setHint("Podaj Nazwe");
                nameEditText.setError("Podaj Nazwe");
            }

            if(givenDetails.equalsIgnoreCase(""))
            {
                failFlag = true;
                detailsEditText.setHint("Podaj Opis");
                detailsEditText.setError("Podaj Opis");
            }

            if(givenLocations.equalsIgnoreCase(""))
            {
                failFlag = true;
                locationEditText.setHint("Podaj imię");
                locationEditText.setError("Podaj imię");
            }

            if(givenSection.equalsIgnoreCase(""))
            {
                failFlag = true;
                locationEditText.setHint("Podaj miejsce");
                locationEditText.setError("Podaj miejsce");
            }

            if (failFlag == false) {
                List<NameValuePair> body = new ArrayList<NameValuePair>();

                body.add(new BasicNameValuePair("id", taskId));
                body.add(new BasicNameValuePair("name", givenName));
                body.add(new BasicNameValuePair("description", givenDetails));
                body.add(new BasicNameValuePair("location", givenLocations));
                body.add(new BasicNameValuePair("scheduled_for", givenDate));
                body.add(new BasicNameValuePair("section_id", givenSection));

                ArrayList<Integer> employeesIds = new ArrayList<Integer>();

                for(int i = 0; i < Employees.size(); i++){
                    if(Employees.get(i).isSelected()){
                        employeesIds.add(Employees.get(i).getId());
                    }
                }

                Gson gson = new Gson();
                String assignedUsers = gson.toJson(employeesIds,
                        new TypeToken<ArrayList<Employee>>() {}.getType());

                body.add(new BasicNameValuePair("assignedUsers", assignedUsers));

                String jsonRequest = gson.toJson(body,
                        new TypeToken<ArrayList<Employee>>() {}.getType());

                System.out.println("WHOLE REQUEST " + body);

                Request request = new Request(currentUser.getToken(),"task/update", body);
                HttpService httpService = new HttpService();

                //Getting response
                String response = httpService.sendRequest(request);

                Intent myIntent = new Intent(this, SectionActivity.class);
                myIntent.putExtra("id", sectionId);
                myIntent.putExtra("currentUser", currentUser);
                myIntent.putExtra("currentSection", currentSection);
                myIntent.putExtra("recreate", true);
                EditTaskActivity.this.startActivity(myIntent);

                if(response.contains("success")){

                    int duration = Toast.LENGTH_SHORT;
                    Toast toast = Toast.makeText(getApplicationContext(), "Zadanie zostalo zedytowane", duration);
                    toast.show();

                    Employees.clear();
                    assignedEmployees.clear();

                }else{
                    int duration = Toast.LENGTH_SHORT;
                    Toast toast = Toast.makeText(getApplicationContext(), "Błąd", duration);
                    toast.show();
                }

            }else{
                int duration = Toast.LENGTH_SHORT;
                Toast toast = Toast.makeText(getApplicationContext(), "Są pola wymagane", duration);
                toast.show();
            }
        }

        if(v.getId() == R.id.addEmployeesButton){

            ArrayList<String> serilizedEmployees = new ArrayList<String>();

            for(int i = 0; i < Employees.size(); i ++){
                Gson gson = new Gson();
                String serilized  = gson.toJson(Employees.get(i));
                serilizedEmployees.add(serilized);
            }
            Intent newIntent = new Intent(this, EditEmployeesActivity.class);
            startActivity(newIntent);
        }

        if(v.getId() == R.id.changeDate){

            Bundle b = new Bundle();
            b.putInt(DatePickerDialogFragment.YEAR, year);
            b.putInt(DatePickerDialogFragment.MONTH, month);
            b.putInt(DatePickerDialogFragment.DATE, day);
            DialogFragment picker = new DatePickerDialogFragment();
            picker.setArguments(b);
            picker.show(getFragmentManager(), "fragment_date_picker");
        }
    }

    public void onItemClick(AdapterView<?> l, View v, int position, long id) {

    }
    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

    }

    @Override
    public void onDateSet(DatePicker view, int year, int monthOfYear,int dayOfMonth)
    {
        this.year = year;
        this.month = monthOfYear;
        this.day = dayOfMonth;

        Calendar calendar = Calendar.getInstance();
        calendar.set(Calendar.YEAR, year);
        calendar.set(Calendar.MONTH, monthOfYear);
        calendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);

        Format formatter = new SimpleDateFormat("yyyy-MM-dd");
        date = formatter.format(calendar.getTime());

        changeDate.setText(date);
    }

    public void onNothingSelected(AdapterView<?> arg0) {
        // TODO Auto-generated method stub

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
