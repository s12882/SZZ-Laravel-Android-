package com.example.myfirstapp.Services;

import com.example.myfirstapp.Models.User;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;

import java.io.IOException;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.RequestBody;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

/**
 * Created by Андрей on 05.01.2018.
 */

public class FirebaseInstanceIDService extends FirebaseInstanceIdService {
    SessionService session;
    @Override
    public void onTokenRefresh() {

        String token = FirebaseInstanceId.getInstance().getToken();
    }

}