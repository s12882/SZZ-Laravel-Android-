package com.example.myfirstapp.Services;

import org.apache.http.NameValuePair;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Андрей on 21.10.2017.
 */

public class Request {
    String token;
    String dest;
    List<NameValuePair> body;

    public Request(String token, String dest, List<NameValuePair> body){
        this.token = token;
        this.dest = dest;
        this.body = body;
    }

    public Request(String token, String dest){
        this.token = token;
        this.dest = dest;
        this.body = new ArrayList<NameValuePair>();
    }

    public String getToken(){
        return this.token;
    }

    public void update(String token, String dest, List<NameValuePair> body){
        this.token = token;
        this.dest = dest;
        this.body = body;
    }

    public String getDest(){
        return this.dest;
    }

    public List getBody(){
        return this.body;
    }
}
