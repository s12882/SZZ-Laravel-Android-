package com.example.myfirstapp.Activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import com.example.myfirstapp.Models.Section;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.example.myfirstapp.Services.AppStatusService;
import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.example.myfirstapp.Services.SessionService;
import com.fasterxml.jackson.core.type.TypeReference;

import org.apache.http.NameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

public class SectionsActivity extends BasicActivity implements View.OnClickListener, AdapterView.OnItemClickListener {

    private String sectionName;
    private ArrayList<Section> Sections = new ArrayList<Section>();
    SessionService session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_sections, null, false);
        drawer_layout.addView(contentView, 0);

        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        User currentUser = User.deSerialize(serializedUser);



        List<NameValuePair> body = new ArrayList<NameValuePair>();
        Request request = new Request(currentUser.getToken(), "section", body);
        HttpService httpService = HttpService.getService();
        String response = httpService.sendRequest(request);

        //Getting all sections
        TypeReference<List<User>> mapType = new TypeReference<List<User>>() {
        };
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

        ListView lvSections = (ListView) findViewById(R.id.sections);
        lvSections.setOnItemClickListener(this);

        ArrayAdapter<Section> adapter = new ArrayAdapter<Section>(this,
                android.R.layout.simple_list_item_1, Sections);
        lvSections.setAdapter(adapter);
    }

    public void onItemClick(AdapterView<?> l, View v, int position, long id) {

        String serializedUser = session.getPref().getString(KEY_USER,"");
        User currentUser = User.deSerialize(serializedUser);

        Section section = (Section) l.getItemAtPosition(position);
        id = section.getId();

        Intent sectionIntent = new Intent();
        sectionIntent.setClass(this, SectionActivity.class);
        sectionIntent.putExtra("currentSection", section);
        sectionIntent.putExtra("id", id);
        startActivity(sectionIntent);
    }

    public void onClick(View v){

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

    @Override
    public void onBackPressed() {

        if(drawerResult.isDrawerOpen()){
            drawerResult.closeDrawer();
        }
        else{
            super.onBackPressed();
        }
    }
}
