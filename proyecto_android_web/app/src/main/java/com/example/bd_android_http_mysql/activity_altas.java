package com.example.bd_android_http_mysql;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.Network;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.annotation.Nullable;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import controlador.AnalizadorJSON;

public class activity_altas extends Activity {

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_altas);
        Spinner spinneredad = findViewById(R.id.spinner_edad);
        Spinner spinnerSemestre = findViewById(R.id.spinner_Semestre);
        Spinner spinnerCarreras = findViewById(R.id.spinner_Carreras);


        List<String> opciones = Arrays.asList(
                "IS",
                "IM",
                "IA",
                "LCP",
                "LA"
        );
        // Crear una lista con edades del 1 al 50
        List<String> edades = new ArrayList<>();
        for (int i = 1; i <= 50; i++) {
            edades.add(String.valueOf(i));
        }

        // Crear una lista con Semestres del 1 al 10
        List<String> semestre = new ArrayList<>();
        for (int i = 1; i <= 10; i++) {
            semestre.add(String.valueOf(i));
        }

        // Crear un adaptador para el Spinner
        ArrayAdapter<String> adapteredad = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, edades);
        adapteredad.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        ArrayAdapter<String> adaptersemestre = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, semestre);
        adapteredad.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        ArrayAdapter<String> adaptercarrera = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, opciones);
        adapteredad.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        // Asignar el adaptador al Spinner
        spinneredad.setAdapter(adapteredad);
        spinnerSemestre.setAdapter(adaptersemestre);
        spinnerCarreras.setAdapter(adaptercarrera);
    }

    public void agregarAlumno(View view){
        /*
            1. Obtener datos de la GUI
            2. Crear instancia de alumno
            3. Invocar al controlador y pasarle los argumentos necesarios
                    url
                    metodo
                    ALUMNO (Array)
         */
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        Network network = cm.getActiveNetwork();

        if(network != null && cm.getNetworkCapabilities(cm.getActiveNetwork()) != null){
            String URL = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_altas.php";
            String metodo = "POST";

            EditText NumControl = findViewById(R.id.caja_Num_Control);
            EditText Nombre = findViewById(R.id.caja_Nombre);
            EditText PrimerAp = findViewById(R.id.caja_Primer_Ap);
            EditText SegundoAp = findViewById(R.id.caja_Segundo_Ap);
            Spinner Edad = findViewById(R.id.spinner_edad);
            Spinner Semestre = findViewById(R.id.spinner_Semestre);
            Spinner Carrera = findViewById(R.id.spinner_Carreras);

            ArrayList<String> datos = new ArrayList();
            datos.add(NumControl.getText().toString());
            datos.add(Nombre.getText().toString());
            datos.add(PrimerAp.getText().toString());
            datos.add(SegundoAp.getText().toString());
            datos.add(Edad.getSelectedItem().toString());
            datos.add(Semestre.getSelectedItem().toString());
            datos.add(Carrera.getSelectedItem().toString());


            AnalizadorJSON analizadorJSON = new AnalizadorJSON();

            new Thread(new Runnable() {
                @Override
                public void run() {
                    JSONObject jsonObject = analizadorJSON.peticionHTTP(URL, metodo, datos);

                    try {
                        String resultado = jsonObject.getString("alta");
                        if (resultado.equals("exito")){


                            runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    Toast.makeText(getBaseContext(),"Insercion Correcta", Toast.LENGTH_LONG).show();
                                }
                            });


                        }
                    } catch (JSONException e) {
                        throw new RuntimeException(e);
                    }
                }
            }).start();



        }else {
            Log.e("MSJ ->", "Error en la red (wifi)");
        }

    }
}
