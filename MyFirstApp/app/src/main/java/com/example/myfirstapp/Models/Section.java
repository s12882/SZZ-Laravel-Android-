package com.example.myfirstapp.Models;

import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.fasterxml.jackson.core.type.TypeReference;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by Андрей on 03.12.2017.
 */

public class Section implements Serializable {

    private int id;
    private String name;
    private static ArrayList<Employee> Employees = new ArrayList<>();
    private static ArrayList<Task> Tasks = new ArrayList<>();

    public Section(int id, String name) {
        this.id = id;
        this.name = name;
    }

    public static ArrayList<Employee> getEmployees() {
        return Employees;
    }

    public static void setEmployees(ArrayList<Employee> employees) {
        Employees = employees;
    }

    public static ArrayList<Task> getTasks() {
        return Tasks;
    }

    public static void setTasks(ArrayList<Task> tasks) {
        Tasks = tasks;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    @Override
    public String toString() {
        return this.id + ". " + this.name + "                                                                             ";
    }

    public void loadEmployees(String token){

        //Building body
        BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("section_id", Integer.toString(getId()));
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(sectionIdBasicNameValuePair);

        Request request = new Request(token, "section/staff", body);
        HttpService httpService = new HttpService();
        String response = httpService.sendRequest(request);

        //Getting all employees of section
        TypeReference<List<User>> mapType = new TypeReference<List<User>>() {
        };
        try {
            JSONObject obj = new JSONObject(response);
            JSONArray jEmployees = obj.getJSONArray("success");

            if (jEmployees != null) {
                for (int i = 0; i < jEmployees.length(); i++) {
                    Employee toList = new Employee(Integer.parseInt(jEmployees.getJSONObject(i).getString("id")),
                            jEmployees.getJSONObject(i).getString("first_name"),
                            jEmployees.getJSONObject(i).getString("surname"));
                    Employees.add(toList);
                    toList = null;
                }
            }
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }
    }

    public void loadTasks(String token){
        //Building body
        BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("section_id", Integer.toString(getId()));
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(sectionIdBasicNameValuePair);

        //Sending request
        Request request = new Request(token, "task/ofsection", body);
        HttpService httpService = new HttpService();
        //Getting response
        String response = httpService.sendRequest(request);

        //Getting all tasks of section
        TypeReference<List<Task>> mapType = new TypeReference<List<Task>>() {
        };
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
    }

}
