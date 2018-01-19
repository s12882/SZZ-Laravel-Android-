package com.example.myfirstapp.Models;

import com.google.gson.Gson;

import java.io.Serializable;

/**
 * Created by Андрей on 06.11.2017.
 */

public class Employee implements Serializable {

    private int id, section_id;
    private String password;
    private String first_name;
    private String surname;
    private String login;
    private String email;
    private String phoneNumber;
    private String sectionName;
    private String role_id;

    boolean isSelected;

    public Employee(){

    }

    public Employee(int id, String first_name, String surname){
        this.id = id;
        this.first_name = first_name;
        this.surname = surname;
    }

    public Employee(String name, String surname, String login, String email, String phoneNumber){
        this.first_name = name;
        this.surname = surname;
        this.login = login;
        this.email = email;
        this.phoneNumber = phoneNumber;
    }

    //GETTERS
    public int getId(){
        return id;
    }

    public String getName(){
        return first_name;
    }

    public String getSurname(){
        return surname;
    }

    public String getLogin(){
        return login;
    }

    public String getEmail(){
        return email;
    }

    public int getSection_id() {
        return section_id;
    }

    public String getSectionName() {
        return sectionName;
    }

    public String getPhoneNumber() {
        return phoneNumber;
    }

    public String getPassword() {
        return password;
    }

    public String getRoleId() {
        return role_id;
    }

    //SETTERS
    public void setId(int id) {
        this.id = id;
    }

    public void setLogin(String login) {
        this.login = login;
    }

    public void setName(String name) {
        this.first_name = name;
    }

    public void setSurname(String surname) {
        this.surname = surname;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setSection_id(int Section_Id){
        this.section_id = Section_Id;
    }

    public  void setSectionName(String sectionName){
        this.sectionName = sectionName;
    }

    public void setPhoneNumber(String phoneNumber) {
        this.phoneNumber = phoneNumber;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setRole_id(String role_id) {
        this.role_id = role_id;
    }

    @Override
    public String toString() {
        return this.id + ". " + this.first_name + ", " + this.surname + "                                                        ";
    }

    public String serialize() {
        Gson gson = new Gson();
        return gson.toJson(this);
    }

    static public Employee deSerialize(String serializedData) {
        Gson gson = new Gson();
        return gson.fromJson(serializedData, Employee.class);
    }

    public boolean isSelected(){
        return isSelected;
    }

    public void setChecked(){
        this.isSelected = true;
    }

    public void setUnChecked(){
        this.isSelected = false;
    }

}
