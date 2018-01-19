package com.example.myfirstapp.Services;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Intent;
import android.support.v4.app.NotificationCompat;

import com.example.myfirstapp.Activities.SectionActivity;
import com.example.myfirstapp.Activities.SectionHomeActivity;
import com.example.myfirstapp.Activities.TaskDetailsActivity;
import com.example.myfirstapp.Models.Task;
import com.example.myfirstapp.Models.User;
import com.example.myfirstapp.R;
import com.google.firebase.messaging.RemoteMessage;

import static com.example.myfirstapp.Services.SessionService.KEY_USER;

/**
 * Created by Андрей on 05.01.2018.
 */

public class FirebaseMessagingService extends com.google.firebase.messaging.FirebaseMessagingService {

    SessionService session;

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        showNotification(remoteMessage.getData().get("message"), remoteMessage.getData().get("theme"), remoteMessage.getData().get("id"));
    }

    private void showNotification(String message, String theme, String id) {

        // Read the shared preference value
        session = new SessionService(getApplicationContext());
        String serializedUser = session.getPref().getString(KEY_USER,"");
        User currentUser = User.deSerialize(serializedUser);
        Intent newIntent = new Intent();
        newIntent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);


        if(theme.equals("Task Create") || theme.equals("Task Update") || theme.equals("Task Reserve") || theme.equals("Task Assign")){
            Task task = new Task();
            task.setId(Integer.parseInt(id));
            session.updateSession(currentUser);
            newIntent.setClass(this, TaskDetailsActivity.class);
            newIntent.putExtra("currentSection", currentUser.getSection());
            newIntent.putExtra("currentTask", task);
            newIntent.putExtra("id", Long.parseLong(id, 10) );
        }

        if(theme.equals("Task Delete")){
            newIntent.setClass(this, SectionHomeActivity.class);
            newIntent.putExtra("Fragment", "TASK");
            newIntent.putExtra("currentUser", currentUser);
            newIntent.putExtra("id", Long.parseLong(id, 10) );
        }

        if(theme.equals("User")){
            newIntent.setClass(this, SectionActivity.class);
        }

        PendingIntent pendingIntent = PendingIntent.getActivity(this,0,newIntent, PendingIntent.FLAG_UPDATE_CURRENT);
        NotificationCompat.Builder builder = new NotificationCompat.Builder(this)
                .setAutoCancel(true)
                .setContentText(message)
                .setSmallIcon(R.drawable.tab_icon_selector)
                .setContentIntent(pendingIntent);

        NotificationManager manager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);

        manager.notify(0,builder.build());
    }
}
