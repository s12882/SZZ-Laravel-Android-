package com.example.myfirstapp.Models;

import java.io.Serializable;

/**
 * Created by macie on 14.01.2018.
 */

public class Task implements Serializable {

    private String name, description, location;
    private int id, section_id;

    public Task(){
    }

    public Task(int id, String name, String description, String location, int section_id) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.location = location;
        this.section_id = section_id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public int getSection_id() {
        return section_id;
    }

    public void setSection_id(int section_id) {
        this.section_id = section_id;
    }

    @Override
    public String toString() {return this.name + "                                                                            ";
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }
}
