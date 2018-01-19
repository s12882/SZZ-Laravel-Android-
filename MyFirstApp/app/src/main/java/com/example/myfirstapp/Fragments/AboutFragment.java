package com.example.myfirstapp.Fragments;

import android.app.Fragment;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;

import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;

public class AboutFragment extends Fragment implements View.OnClickListener, AdapterView.OnItemClickListener {

    public AboutFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_about, null);

    }

    public void onItemClick(AdapterView<?> l, View v, int position, long id) {


    }

    public void onClick(View v){


    }

}