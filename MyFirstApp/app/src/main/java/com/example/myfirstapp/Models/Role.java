package com.example.myfirstapp.Models;

/**
 * Created by macie on 15.01.2018.
 */

public class Role {

    private int id;
    private String name;

    public Role(){

    }

    public Role(int id, String name) {
        this.id = id;
        this.name = name;
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
        return this.id + ". " + this.name;
    }
}
