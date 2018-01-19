package com.example.myfirstapp.Models;

/**
 * Created by Андрей on 02.11.2017.
 */

import com.example.myfirstapp.Services.HttpService;
import com.example.myfirstapp.Services.Request;
import com.fasterxml.jackson.core.type.TypeReference;
import com.google.gson.Gson;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class User implements Serializable {

    private int id;
    private String  firstName, surname, login, email, phoneNumber, token, sectionName;
    private String username, password;
    private String PIN;
    private ArrayList<String> permissions;
    private int section_id, role_id;

    private Section section;
    private Role role;
    private ArrayList<Task> userTasks;

    public User(int role_id, String token, int section_id){
        this.role_id = role_id;
        this.token = token;
        this.section_id = section_id;
    }

    public User(){

    }

    public Section getSection() {
        return section;
    }

    public void setSection(Section section) {
        this.section = section;
    }

    public void setRole(Role role) {
        this.role = role;
    }

    public Role getRole() {
        return role;
    }

    public ArrayList<Task> getUserTasks(){
        return userTasks;
    }

    public void setUserTasks(ArrayList<Task> tasks){
        this.userTasks = tasks;
    }

    public void assignTask(Task task){
        this.userTasks.add(task);
    }

    public void clearTasks(Task task){
        this.userTasks.clear();
    }

    //----------GETTERS AND SETTERS----------//

    public void setPassword(String password) {
        this.password = password;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setToken(String token) {
        this.token = token;
    }

    public void setRole(int role_id){
        this.role_id = role_id;
    }

    public void setSectionId(int id){
        this.section_id = id;
    }

    public void setPIN(String PIN){
        this.PIN = PIN;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setPermissions(ArrayList<String> permissions){
        this.permissions = permissions;
    }

    public int getRoleId(){
        return this.role_id;
    }

    public String getToken() { return this.token; }

    public String getPIN(){ return this.PIN; }

    public int getSectionId() {return section_id; }

    public ArrayList<String> getPermissions(){
        return permissions;
    }

    public String getSectionName() {
        return sectionName;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getSurname() {
        return surname;
    }

    public void setSurname(String surname) {
        this.surname = surname;
    }

    public String getLogin() {
        return login;
    }

    public void setLogin(String login) {
        this.login = login;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPhoneNumber() {
        return phoneNumber;
    }

    public void setPhoneNumber(String phoneNumber) {
        this.phoneNumber = phoneNumber;
    }

    public void setSectionName(String sectionName) {
        this.sectionName = sectionName;
    }

    @Override
    public String toString() {
        return "User [token=" + this.token + ", role=" + this.role_id + ", section_id=" + this.section_id + "]";
    }

    public String serialize() {
        Gson gson = new Gson();
        return gson.toJson(this);
    }

    static public User deSerialize(String serializedData) {
        // Use GSON to instantiate this class using the JSON representation of the state
        Gson gson = new Gson();
        return gson.fromJson(serializedData, User.class);
    }

    public boolean hasPermission(String permission){
        if(getPermissions().contains(permission)){
            return true;
        }else
            return false;
    }
    public void loadTasks(){
        //Building body
        BasicNameValuePair userIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(getId()));
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(userIdBasicNameValuePair);

        //Sending request
        Request request = new Request(this.getToken(), "user/tasks", body);
        HttpService httpService = new HttpService();
        //Getting response
        String response = httpService.sendRequest(request);
        try {
            ArrayList<Task> Tasks = new ArrayList<>();
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
            this.userTasks = Tasks;
        } catch (Exception ioe) {
            ioe.printStackTrace();
        }
    }

    public void loadSection(){
        //Building body
        BasicNameValuePair sectionIdBasicNameValuePair = new BasicNameValuePair("id", Integer.toString(getSectionId()));
        List<NameValuePair> body = new ArrayList<NameValuePair>();
        body.add(sectionIdBasicNameValuePair);

        Request request = new Request(this.getToken(), "section/get", body);
        HttpService httpService = HttpService.getService();
        String response = httpService.sendRequest(request);

        try {
            JSONObject obj = new JSONObject(response);
            this.section = new Section(obj.getJSONObject("success").getInt("id"),
                    obj.getJSONObject("success").getString("name"));

        } catch (Exception ioe) {
            ioe.printStackTrace();
        }
    }

    public void loadRole(){

        List<NameValuePair> body = new ArrayList<NameValuePair>();
        Request request = new Request(this.getToken(), "roles/get", body);
        HttpService httpService = HttpService.getService();
        String response = httpService.sendRequest(request);

        try {
            JSONObject obj = new JSONObject(response);
            this.role = new Role(obj.getJSONObject("success").getInt("id"),
                    obj.getJSONObject("success").getString("name"));

        } catch (Exception ioe) {
            ioe.printStackTrace();
        }

    }

    public boolean hasTask(Task task){
        for(int i = 0; i<this.userTasks.size(); i++){
            System.out.println("HAS TASK???" + this.userTasks.get(i).getId() + "222 " + task.getId());
            if(this.userTasks.get(i).getId() == task.getId()){
                return true;
            }
        }
        return false;
    }

    public void update(){
        Request requestDetails = new Request(this.getToken(), "details");
        HttpService httpService = new HttpService();
        String responseDetails = httpService.sendRequest(requestDetails);

        try {
            JSONObject obj = new JSONObject(responseDetails);

            this.setId(obj.getJSONObject("success").getInt("id"));
            this.setLogin(obj.getJSONObject("success").getString("login"));
            this.setFirstName(obj.getJSONObject("success").getString("first_name"));
            this.setSurname(obj.getJSONObject("success").getString("surname"));
            this.setPhoneNumber(obj.getJSONObject("success").getString("phoneNumber"));
            this.setEmail(obj.getJSONObject("success").getString("email"));
            this.loadTasks();

            this.setRole(Integer.parseInt(obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getString("id")));
            this.loadRole();

            if (obj.getJSONObject("success").getString("section_id") != "null") {
                this.setSectionId(obj.getJSONObject("success").getInt("section_id"));
            } else {
                this.setSectionId(1);
            }
            this.loadSection();

            //**Pobieramy i ustawiamy uprawnienia
            JSONArray jPermissions = obj.getJSONObject("success").getJSONArray("roles").getJSONObject(0).getJSONArray("permissions");
            ArrayList<String> permissions = new ArrayList<String>();
            if (jPermissions != null) {
                for (int i = 0; i < jPermissions.length(); i++) {
                    permissions.add(jPermissions.getJSONObject(i).getString("name"));
                }
            }
            this.setPermissions(permissions);

        }catch (Exception JRE){
            JRE.printStackTrace();
        }
    }

}
