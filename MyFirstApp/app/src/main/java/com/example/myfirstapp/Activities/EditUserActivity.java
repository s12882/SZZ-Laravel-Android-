package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.myfirstapp.Models.Employee;
import com.example.myfirstapp.Models.Role;
import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.example.myfirstapp.Services.SessionService;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class EditUserActivity extends BasicActivity implements View.OnClickListener, AdapterView.OnItemSelectedListener  {

    private EditText loginEditText, passwordEditText, nameEditText, surnameEditText, phoneEditText,
            emailEditText;
    private Spinner sectionDropdown, rolesDropdown;
    private Button sendPostReqButton;
    private Employee currentEmployee;
    Section currentSection;
    User currentUser;
    SessionService session;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_edit_user, null, false);
        drawer_layout.addView(contentView, 0);

        Intent intent = getIntent();
        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        currentUser = User.deSerialize(serializedUser);
        currentSection = (Section)intent.getSerializableExtra("currentSection");
        currentEmployee = (Employee)intent.getSerializableExtra("currentEmployee");

        loginEditText = (EditText) findViewById(R.id.newLoginBox);
        passwordEditText = (EditText) findViewById(R.id.newPasswordBox);
        nameEditText = (EditText) findViewById(R.id.newNameBox);
        surnameEditText = (EditText) findViewById(R.id.newSurnameBox);
        phoneEditText = (EditText) findViewById(R.id.newTelefonBox);
        emailEditText = (EditText) findViewById(R.id.newEmailBox);
        sectionDropdown = (Spinner) findViewById(R.id.newSectionSpinner);
        rolesDropdown = (Spinner) findViewById(R.id.newRoleSpinner);

        loginEditText.setText(currentEmployee.getLogin());
        nameEditText.setText(currentEmployee.getName());
        surnameEditText.setText(currentEmployee.getSurname());
        phoneEditText.setText(currentEmployee.getPhoneNumber());
        emailEditText .setText(currentEmployee.getEmail());
        sectionDropdown.setSelection(currentEmployee.getSection_id());


        //-----------GETTING ALL SECTIONS------------///
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        Request request = new Request(currentUser.getToken(),"section", body);
        HttpService httpService = new HttpService();

        //Getting response
        String response = httpService.sendRequest(request);

        //Getting all sections
        ArrayList<Section> Sections = new ArrayList<Section>();
        try {
            JSONObject obj = new JSONObject(response);
            JSONArray jSections = obj.getJSONArray("success");

            if (jSections != null) {
                for (int i = 0; i < jSections.length(); i++) {
                    Section toList = new Section(Integer.parseInt(jSections.getJSONObject(i).getString("id")),
                            jSections.getJSONObject(i).getString("name"));
                    Sections.add(toList);
                    System.out.println("!!!!!111" + toList);
                    toList = null;
                }
            }

        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        //-----------GETTING ALL ROLES------------///
        //Sending request
        body.clear();
        request.update(currentUser.getToken(),"roles/all", body);

        //Getting response
        String rolesResponse = httpService.sendRequest(request);

        ArrayList<Role> Roles = new ArrayList<Role>();
        try {
            JSONObject obj = new JSONObject(rolesResponse);
            JSONArray jRoles = obj.getJSONArray("success");

            if (jRoles != null) {
                for (int i = 0; i < jRoles.length(); i++) {
                    Role toList = new Role(Integer.parseInt(jRoles.getJSONObject(i).getString("id")),
                            jRoles.getJSONObject(i).getString("name"));
                    Roles.add(toList);
                    toList = null;
                }
            }
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        //Sections dropdown list
        ArrayAdapter<Section> sectionAdapter = new ArrayAdapter<Section>(this, android.R.layout.simple_spinner_dropdown_item, Sections);
        sectionAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        sectionDropdown.setAdapter(sectionAdapter);

        //Roles dropdown list
        ArrayAdapter<Role> rolesAdapter = new ArrayAdapter<Role>(this, android.R.layout.simple_spinner_dropdown_item, Roles);
        rolesAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        rolesDropdown.setAdapter(rolesAdapter);

        sendPostReqButton = (Button) findViewById(R.id.createUserButton);
        sendPostReqButton.setOnClickListener(this);
        sectionDropdown.setOnItemSelectedListener(this);
    }

    public void onClick(View v) {

        if(v.getId() == R.id.createUserButton){
            String id = Integer.toString(currentEmployee.getId());
            String givenLogin = loginEditText.getEditableText().toString();
            String givenPassword = passwordEditText.getEditableText().toString();
            String givenName = nameEditText.getEditableText().toString();
            String givenSurname = surnameEditText.getEditableText().toString();
            String givenPhone = phoneEditText.getEditableText().toString();
            String givenEmail = emailEditText.getEditableText().toString();

            Section section = (Section) sectionDropdown.getSelectedItem();
            int section_id = section.getId();
            String givenSection = String.valueOf(section_id);

            Role role = (Role) rolesDropdown.getSelectedItem();
            int roleId = role.getId();
            String givenRole = String.valueOf(roleId);

            //Validation
            boolean failFlag = false;
            if(givenLogin.equalsIgnoreCase(""))
            {
                failFlag = true;
                loginEditText.setHint("Podaj login");
                loginEditText.setError("Podaj login");
            }


            if(givenName.equalsIgnoreCase(""))
            {
                failFlag = true;
                nameEditText.setHint("Podaj imię");
                nameEditText.setError("Podaj imię");
            }

            if(givenSurname.equalsIgnoreCase(""))
            {
                failFlag = true;
                surnameEditText.setHint("Podaj nazwisko");
                surnameEditText.setError("Podaj nazwisko");
            }

            if(givenPhone.equalsIgnoreCase(""))
            {
                failFlag = true;
                phoneEditText.setHint("Podaj telefon");
                phoneEditText.setError("Podaj telefon");
            }

            if(givenEmail.equalsIgnoreCase(""))
            {
                failFlag = true;
                emailEditText.setHint("Podaj email");
                emailEditText.setError("Podaj email");
            }

            if (failFlag == false) {

                List<NameValuePair> body = new ArrayList<NameValuePair>();
                body.add(new BasicNameValuePair("id", id));
                body.add(new BasicNameValuePair("login", givenLogin));
                body.add(new BasicNameValuePair("first_name", givenName));
                body.add(new BasicNameValuePair("surname", givenSurname));
                body.add(new BasicNameValuePair("phoneNumber", givenPhone));
                body.add(new BasicNameValuePair("email", givenEmail));
                body.add(new BasicNameValuePair("section_id", givenSection));
                body.add(new BasicNameValuePair("role_id", givenRole));

                if(!givenPassword.equalsIgnoreCase(""))
                {
                    body.add(new BasicNameValuePair("password", givenPassword));
                }

                //Sending request
                Request request = new Request(currentUser.getToken(),"user/update", body);
                HttpService httpService = new HttpService();
                //Getting response
                String response = httpService.sendRequest(request);

                if(response.contains("success")){
                    Intent myIntent = new Intent(this, SectionActivity.class);
                    myIntent.putExtra("currentUser", currentUser);
                    myIntent.putExtra("currentSection", currentSection);
                    myIntent.putExtra("recreate", true);
                    EditUserActivity.this.startActivity(myIntent);

                    Toast toast = Toast.makeText(getApplicationContext(), "Użytkownik zedytowany",  Toast.LENGTH_SHORT);
                    toast.show();
                }else{
                    Toast toast = Toast.makeText(getApplicationContext(), "Błąd podczas edycji", Toast.LENGTH_SHORT);
                    toast.show();
                }

            }else{
                Toast toast = Toast.makeText(getApplicationContext(), "Są pola wymagane", Toast.LENGTH_SHORT);
                toast.show();
            }
        }
    }

    public void onItemClick(AdapterView<?> l, View v, int position, long id) {

    }
    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

    }

    public void onNothingSelected(AdapterView<?> arg0) {

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
