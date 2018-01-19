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

import com.example.myfirstapp.Activities.CreateTaskActivity;
import com.example.myfirstapp.Activities.TaskDetailsActivity;
import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.Task;
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

public class TasksFragment extends Fragment  implements View.OnClickListener, AdapterView.OnItemClickListener{

    private String token;
    private User currentUser;
    private Section currentSection;
    private Task task;

    private Button createTaskButton;

    private OnFragmentInteractionListener mListener;

    public TasksFragment() {
        // Required empty public constructor
    }

    public static TasksFragment newInstance(User currentUser, Section currentSection) {
        TasksFragment fragment = new TasksFragment();
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

        return inflater.inflate(R.layout.fragment_tasks, container, false);
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
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(sectionIdBasicNameValuePair);

        //Sending request
        Request request = new Request(currentUser.getToken(), "task/ofsection", body);
        HttpService httpService = new HttpService();
        //Getting response
        String response = httpService.sendRequest(request);

        ArrayList<Task> Tasks = new ArrayList<Task>();

        //Getting all tasks of section
        try {
            JSONObject obj = new JSONObject(response);
            JSONArray jTasks = obj.getJSONArray("success");

            if (jTasks != null) {
                for (int i = 0; i < jTasks.length(); i++) {
                    Task toList = new Task( jTasks.getJSONObject(i).getInt("id"),
                            jTasks.getJSONObject(i).getString("name"),
                    jTasks.getJSONObject(i).getString("description"),
                            jTasks.getJSONObject(i).getString("location"),
                            jTasks.getJSONObject(i).getInt("section_id"));
                    Tasks.add(toList);
                    toList = null;
                }
            }

        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

        ListView lvWorkers = (ListView) getView().findViewById(R.id.tasks);
        lvWorkers.setOnItemClickListener(this );

        ArrayAdapter<Task> adapter = new ArrayAdapter<Task>(getView().getContext(), android.R.layout.simple_list_item_1, Tasks);
        lvWorkers.setAdapter(adapter);


        createTaskButton = (Button) getView().findViewById(R.id.addTaskButton);
        if(currentUser.hasPermission("create task")) {
            createTaskButton.setOnClickListener(this);
            createTaskButton.bringToFront();
        }else{
            createTaskButton.setVisibility(View.GONE);
        }
    }

    public void onItemClick(AdapterView<?> l, View v, int position, long id) {

        token = getArguments().getString("token");
        currentUser = (User) getArguments().getSerializable("currentUser");

        Task task = (Task)l.getItemAtPosition(position);

        Intent newIntent = new Intent();
        newIntent.setClass(getView().getContext(), TaskDetailsActivity.class);
        newIntent.putExtra("currentSection", currentSection);
        newIntent.putExtra("currentTask", task);
        startActivity(newIntent);
    }

    public void onClick(View v){

        if(v.getId() == R.id.addTaskButton){
            currentUser = (User) getArguments().getSerializable("currentUser");
            Intent createIntent = new Intent();
            createIntent.setClass(getView().getContext(), CreateTaskActivity.class);
            createIntent.putExtra("currentSection", currentSection);
            startActivity(createIntent);
        }
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    public interface OnFragmentInteractionListener {
        // TODO: Update argument type and name
        void onFragmentInteraction(Uri uri);
    }
}
