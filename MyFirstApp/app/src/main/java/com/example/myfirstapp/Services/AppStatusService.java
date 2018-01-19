package com.example.myfirstapp.Services;

import android.app.Application;
import android.app.Activity;
import android.app.Application.ActivityLifecycleCallbacks;
import android.content.BroadcastReceiver;
import android.content.ComponentCallbacks2;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;

/**
 * Created by Андрей on 19.11.2017.
 */

//This class is used to check if application gone to background, and which time it spend there.
//If it was too long on background then we ask to enter a personal PIN.
public class AppStatusService extends Application implements
        ActivityLifecycleCallbacks, ComponentCallbacks2 {

    private static String TAG = AppStatusService.class.getName();

    public boolean checkPin;
    public static String stateOfLifeCycle = "";
    public static boolean wasInBackground = false;
    public static long timeElapsed = 0;
    public static long goneToBackground = 0;
    public static long goneToForeground = 0;

    ScreenOffReceiver screenOffReceiver = new ScreenOffReceiver();

    @Override
    public void onCreate() {
        super.onCreate();
        registerActivityLifecycleCallbacks(this);

        registerReceiver(screenOffReceiver, new IntentFilter(
                "android.intent.action.SCREEN_OFF"));
    }

    @Override
    public void onActivityCreated(Activity activity, Bundle arg1) {
        wasInBackground = false;
        stateOfLifeCycle = "Create";
    }

    @Override
    public void onActivityStarted(Activity activity) {
        stateOfLifeCycle = "Start";
    }

    @Override
    public void onActivityResumed(Activity activity) {
        stateOfLifeCycle = "Resume";
    }

    @Override
    public void onActivityPaused(Activity activity) {
        stateOfLifeCycle = "Pause";
        goneToBackground = System.currentTimeMillis();
    }

    @Override
    public void onActivityStopped(Activity activity) {
        stateOfLifeCycle = "Stop";
    }

    @Override
    public void onActivitySaveInstanceState(Activity activity, Bundle arg1) {
    }

    @Override
    public void onActivityDestroyed(Activity activity) {
        wasInBackground = false;
        stateOfLifeCycle = "Destroy";
    }

    @Override
    public void onTrimMemory(int level) {
        if (stateOfLifeCycle.equals("Stop")) {
            wasInBackground = true;
        }
        super.onTrimMemory(level);
    }

    class ScreenOffReceiver extends BroadcastReceiver {

        @Override
        public void onReceive(Context context, Intent intent) {
            wasInBackground = true;
        }
    }


}
