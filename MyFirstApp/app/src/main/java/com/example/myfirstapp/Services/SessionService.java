package com.example.myfirstapp.Services;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import com.example.myfirstapp.Activities.LoginActivity;
import com.example.myfirstapp.Models.User;
import com.google.gson.Gson;

import java.util.HashMap;

/**
 * Created by macie on 13.01.2018.
 */

public class SessionService {

    SharedPreferences pref;

    // Editor for Shared preferences
    SharedPreferences.Editor editor;

    // Context
    Context _context;

    // Shared pref mode
    int PRIVATE_MODE = 0;

    // Sharedpref file name
    private static final String PREF_NAME = "Session";

    // All Shared Preferences Keys
    private static final String IS_LOGIN = "IsLoggedIn";

    // User name (make variable public to access from outside)
    public static final String KEY_LOGIN = "login";

    // User name (make variable public to access from outside)
    public static final String KEY_PASS = "password";

    // User name (make variable public to access from outside)
    public static final String REMEMBER_ME = "rememberMe";

    // User name (make variable public to access from outside)
    public static final String KEY_USER = "user";

//    public static User currentUser;


    // Constructor
    public SessionService(Context context){
            this._context = context;
            pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
            editor = pref.edit();
    }

    public SharedPreferences getPref(){
        return pref;
    }

    public SharedPreferences.Editor getEditor(){
        return editor;
    }

    /**
     * Create login session
     * */
    public void createLoginSession(User currentUser){
        // Storing login value as TRUE
        editor.putBoolean(IS_LOGIN, true);

        // Serialize this class into a JSON string using GSON
        Gson gson = new Gson();
        String sessionUser = gson.toJson(currentUser);

        editor.putString(KEY_USER, sessionUser);

        editor.commit();
    }

    /**
     * Check login method wil check user login status
     * If false it will redirect user to login page
     * Else won't do anything
     * */
    public void checkLogin(){
        // Check login status
        if(!this.isLoggedIn()){
            // user is not logged in redirect him to Login Activity
            Intent i = new Intent(_context, LoginActivity.class);
            // Closing all the Activities
            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            // Add new Flag to start new Activity
            i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

            // Staring Login Activity
            _context.startActivity(i);
        }

    }

    /**
     * Quick check for login
     * **/
    // Get Login State
    public boolean isLoggedIn(){
        return pref.getBoolean(IS_LOGIN, false);
    }

    public HashMap<String, String> getUserDetails(){
        HashMap<String, String> user = new HashMap<String, String>();
        // user name
        user.put(KEY_LOGIN, pref.getString(KEY_LOGIN, null));

        // user email id
        user.put(KEY_PASS, pref.getString(KEY_PASS, null));

        // return user
        return user;
    }

    public void updateSession(User currentUser){
        currentUser.update();

        Gson gson = new Gson();
        String sessionUser = gson.toJson(currentUser);

        editor.putString(KEY_USER, sessionUser);
        editor.commit();
    }

    public void synchronize(User currentUser){
        Gson gson = new Gson();
        String sessionUser = gson.toJson(currentUser);

        editor.putString(KEY_USER, sessionUser);
        editor.commit();
    }

    /**
     * Clear session details
     * */
    public void logoutUser(){
        // Clearing all data from Shared Preferences
        editor.clear();
        editor.commit();

        // After logout redirect user to Loing Activity
        Intent i = new Intent(_context, LoginActivity.class);
        // Closing all the Activities
        i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

        // Add new Flag to start new Activity
        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

        // Staring Login Activity
        _context.startActivity(i);
    }


}
