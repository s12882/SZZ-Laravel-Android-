package com.example.myfirstapp.Services;

import android.os.AsyncTask;

import org.apache.http.HttpHeaders;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;


/**
 * Created by Андрей on 14.10.2017.
 */

public class HttpService{

    public String response;
    static HttpService httpService = new HttpService();
    static HttpClient httpClient = new DefaultHttpClient();

    public static HttpService getService(){
        return httpService;
    }

    public static HttpClient getClient() {
        return httpClient;
    }

    void getData(String response){
        this.response = response;
    }

    public String sendRequest(Request request) {

        String token = request.getToken();
        String dest = request.getDest();
        List body = request.getBody();

         class SendPostReqAsyncTask extends AsyncTask<Object, Void, String> {

            @Override
            protected String doInBackground(Object... params) {

                String token = (String) params[0].toString();
                String dest = (String) params[1].toString();
                List<NameValuePair> body = (ArrayList<NameValuePair>)params[2];

                System.out.println("*** doInBackground ** paramToken " + token);

                HttpClient httpClient = HttpService.getClient();
                HttpPost httpPost = new HttpPost("http://10.0.2.2:8000/api/" + dest);
                httpPost.setHeader(HttpHeaders.CONTENT_TYPE, "application/x-www-form-urlencoded");
                httpPost.setHeader(HttpHeaders.ACCEPT, "application/json");
                httpPost.setHeader(HttpHeaders.AUTHORIZATION, "Bearer " + token);
                httpPost.addHeader("cookie",  "username");

                try {
                    // UrlEncodedFormEntity-to entity  składająca się z listy URL-kodowanej pary.
                    //Zazwyczaj jest to przydatne podczas wysyłania polecenia http Post.
                    UrlEncodedFormEntity urlEncodedFormEntity = new UrlEncodedFormEntity(body);
                    httpPost.setEntity(urlEncodedFormEntity);

                    try {
                        // HttpResponse inrterfejs, tak samo jak HttpPost.
                        HttpResponse httpResponse = httpClient.execute(httpPost);

                        // Zgodnie z java API , konstruktor inputstream nic nie robię.
                        //Więc nie możemy zainicjować klienta inputstream choć nie jest to interfejs
                        InputStream inputStream = httpResponse.getEntity().getContent();

                        InputStreamReader inputStreamReader = new InputStreamReader(inputStream);

                        BufferedReader bufferedReader = new BufferedReader(inputStreamReader);

                        StringBuilder stringBuilder = new StringBuilder();

                        String bufferedStrChunk = null;

                        while((bufferedStrChunk = bufferedReader.readLine()) != null){
                            stringBuilder.append(bufferedStrChunk);
                        }

                        return stringBuilder.toString();

                    } catch (ClientProtocolException cpe) {
                        System.out.println("First Exception caz of HttpResponese :" + cpe);
                        cpe.printStackTrace();
                    } catch (IOException ioe) {
                        System.out.println("Second Exception caz of HttpResponse :" + ioe);
                        ioe.printStackTrace();
                    }

                } catch (UnsupportedEncodingException uee) {
                    System.out.println("An Exception given because of UrlEncodedFormEntity argument :" + uee);
                    uee.printStackTrace();
                }
                return null;
            }

            @Override
            protected void onPostExecute(String result) {
                super.onPostExecute(result);
                if(result.contains("success")){
                    getData(result);
                }
            }
        }

        SendPostReqAsyncTask sendPostReqAsyncTask = new SendPostReqAsyncTask();
        try{
            String str_result = sendPostReqAsyncTask.execute(token, dest, body).get();
            return str_result;
        }catch(Exception ioe){
            System.out.println("Timed Out... :" + ioe);
            ioe.printStackTrace();
            return "Error occured...";
        }
    }

}
