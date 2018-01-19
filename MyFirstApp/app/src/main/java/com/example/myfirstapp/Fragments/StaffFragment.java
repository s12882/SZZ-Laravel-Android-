package com.example.myfirstapp.Fragments;

import android.app.Fragment;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;

import com.example.myfirstapp.Activities.CreateUserActivity;
import com.example.myfirstapp.Activities.EmployeeDetailsActivity;
import com.example.myfirstapp.Models.Employee;
import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.fasterxml.jackson.core.type.TypeReference;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;


public class StaffFragment extends Fragment implements View.OnClickListener, AdapterView.OnItemClickListener {

    private String token;
    private User currentUser;
    private Section currentSection;
    private View view;

    private Button crateUserButton;
    private OnFragmentInteractionListener mListener;

    public StaffFragment() {
        // Required empty public constructor
    }

    public static StaffFragment newInstance(User currentUser, Section currentSection) {
        StaffFragment fragment = new StaffFragment();
        Bundle args = new Bundle();
        args.putString("token", currentUser.getToken());
        args.putSerializable("currentUser", currentUser);
        args.putSerializable("currentSection", currentSection);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        return inflater.inflate(R.layout.fragment_staff, container, false);
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        if (getArguments() != null) {
            token = getArguments().getString("token");
            currentUser = (User) getArguments().getSerializable("currentUser");
            currentSection = (Section) getArguments().getSerializable("currentSection");
        }

        //Building body
        BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("section_id", Integer.toString(currentSection.getId()));
        List<NameValuePair> body = new ArrayList<>();
        body.add(sectionIdBasicNameValuePair);

        //Sending request
        Request request = new Request(currentUser.getToken(), "section/staff", body);
        HttpService httpService = new HttpService();
        //Getting response
        String response = httpService.sendRequest(request);

        ArrayList<Employee> Employees = new ArrayList<>();

        try {
            JSONObject obj = new JSONObject(response);
            JSONArray jEmployees = obj.getJSONArray("success");

            if (jEmployees != null) {
                for (int i = 0; i < jEmployees.length(); i++) {
                    Employee toList = new Employee(Integer.parseInt(jEmployees.getJSONObject(i).getString("id")),
                            jEmployees.getJSONObject(i).getString("first_name"),
                            jEmployees.getJSONObject(i).getString("surname"));
                    Employees.add(toList);
                }
            }

        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        ListView lvWorkers = (ListView) getView().findViewById(R.id.workers);
        lvWorkers.setOnItemClickListener(this);
        ArrayAdapter<Employee> adapter = new ArrayAdapter<>(getView().getContext(), android.R.layout.simple_list_item_1, Employees);
        lvWorkers.setAdapter(adapter);

        if(currentUser.getPermissions().contains("create user")) {
            crateUserButton = (Button) getView().findViewById(R.id.addUserButton);
            crateUserButton.setOnClickListener(this);
            crateUserButton.bringToFront();
        }else{
            crateUserButton.setVisibility(View.GONE);
        }
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    public void onItemClick(AdapterView<?> Item, View v, int position, long id) {

        token = getArguments().getString("token");
        currentUser = (User) getArguments().getSerializable("currentUser");

        Employee employee = (Employee)Item.getItemAtPosition(position);
        id = employee.getId();

        Intent newIntent = new Intent();
        newIntent.setClass(getView().getContext(), EmployeeDetailsActivity.class);
        newIntent.putExtra("currentUser", currentUser);
        newIntent.putExtra("currentSection", currentSection);
        newIntent.putExtra("currentEmployee", employee);
        startActivity(newIntent);
    }

    public void onClick(View v){

        if(v.getId() == R.id.addUserButton){
            currentUser = (User) getArguments().getSerializable("currentUser");
            Intent createIntent = new Intent();
            createIntent.setClass(getView().getContext(), CreateUserActivity.class);
            createIntent.putExtra("currentSection", currentSection);
            startActivity(createIntent);
        }
    }

    // TODO: Rename method, update argument and hook method into UI event
    public void onButtonPressed(Uri uri) {
        if (mListener != null) {
            mListener.onFragmentInteraction(uri);
        }
    }

    public interface OnFragmentInteractionListener {
        // TODO: Update argument type and name
        void onFragmentInteraction(Uri uri);
    }
}
