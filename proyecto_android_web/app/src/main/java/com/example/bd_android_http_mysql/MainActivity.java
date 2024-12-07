package com.example.bd_android_http_mysql;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }

    public void abrirActivities(View view){

        Intent i = null;
        if(view.getId() == R.id.btn_agregar){
            i = new Intent(this, activity_altas.class);
        } else if (view.getId() == R.id.btn_consultas) {
            i = new Intent(this, activity_consultas.class);
        } else if (view.getId() == R.id.btn_bajas) {
            i = new Intent(this, activity_bajas.class);
        }else if (view.getId() == R.id.btn_cambios) {
            i = new Intent(this, activity_cambios.class);
        }
        startActivity(i);

    }
}