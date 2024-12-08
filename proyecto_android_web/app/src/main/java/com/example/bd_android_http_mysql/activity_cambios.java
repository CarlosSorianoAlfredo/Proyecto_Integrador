package com.example.bd_android_http_mysql;

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
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import controlador.AnalizadorJSON;

public class activity_cambios extends Activity {
    RecyclerView recyclerView;
    EditText cajaNumControl, cajaNombre, cajaPrimerAp, cajaSegundoAp;
    Spinner spinnerEdad, spinnerSemestre;
    RecyclerView.Adapter adapter;
    RecyclerView.LayoutManager layoutManager;
    String NumControl, carrera;
    ArrayList<String> registroFiltro = new ArrayList<>();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cambios);

        // Enlazar componentes con su ID
        recyclerView = findViewById(R.id.reciyclerView_Cambios);
        cajaNumControl = findViewById(R.id.caja_Num_control_a_buscar);
        cajaNombre = findViewById(R.id.caja_nombremodificar);
        cajaPrimerAp = findViewById(R.id.Caja_PrimerApModificar);
        cajaSegundoAp = findViewById(R.id.Caja_SegundoApModificar);
        spinnerEdad = findViewById(R.id.spinner_EdadModificar);
        spinnerSemestre = findViewById(R.id.spinner_SemestreModificar);

        recyclerView.setHasFixedSize(true);
        layoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(layoutManager);

        // Configurar spinners de edades y semestres
        List<String> edades = new ArrayList<>();
        for (int i = 1; i <= 50; i++) {
            edades.add(String.valueOf(i));
        }
        List<String> semestres = new ArrayList<>();
        for (int i = 1; i <= 10; i++) {
            semestres.add(String.valueOf(i));
        }

        ArrayAdapter<String> adapterEdad = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, edades);
        adapterEdad.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerEdad.setAdapter(adapterEdad);

        ArrayAdapter<String> adapterSemestre = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, semestres);
        adapterSemestre.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerSemestre.setAdapter(adapterSemestre);
    }

    // Método para buscar y cargar datos en los componentes
    public void buscarDatos(View view) {
        String numControl = cajaNumControl.getText().toString();
        if (numControl.isEmpty()) {
            Toast.makeText(this, "Por favor ingrese un número de control", Toast.LENGTH_SHORT).show();
            return;
        }

        // Verificar conexión a internet
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        Network network = cm.getActiveNetwork();

        if (network != null && cm.getNetworkCapabilities(network) != null) {
            String URL = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_consultas.php";
            String metodo = "POST";


            NumControl = cajaNumControl.getText().toString();

            AnalizadorJSON analizadorJSON = new AnalizadorJSON();

            new Thread(() -> {
                JSONObject jsonObject = analizadorJSON.peticionHTTPConsultasFiltro(URL, metodo, NumControl);

                try {
                    String resultado = jsonObject.getString("consulta");
                    if (resultado.equals("exito")) {
                        JSONArray alumnos = jsonObject.getJSONArray("alumnos");
                        if (alumnos.length() > 0) {
                            JSONObject alumno = alumnos.getJSONObject(0);  // Obtener el primer alumno
                            String nombre = alumno.getString("n");
                            String primerAp = alumno.getString("pa");
                            String segundoAp = alumno.getString("sa");
                            String edad = alumno.getString("e");
                            String semestre = alumno.getString("s");
                            carrera = alumno.getString("c");

                            runOnUiThread(() -> {
                                cajaNombre.setText(nombre);
                                cajaPrimerAp.setText(primerAp);
                                cajaSegundoAp.setText(segundoAp);
                                spinnerEdad.setSelection(getIndexFromSpinner(spinnerEdad, edad));
                                spinnerSemestre.setSelection(getIndexFromSpinner(spinnerSemestre, semestre));
                            });
                        }
                    } else {
                        runOnUiThread(() -> Toast.makeText(this, "No se encontró el alumno", Toast.LENGTH_SHORT).show());
                    }
                } catch (JSONException e) {
                    throw new RuntimeException(e);
                }
            }).start();

        } else {
            Log.e("MSJ ->", "Error en la red (wifi)");
        }
    }

    // Método para confirmar y realizar cambios
    public void confirmarCambios(View view) {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        Network network = cm.getActiveNetwork();

        if (network != null && cm.getNetworkCapabilities(network) != null) {
            String URL = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_cambios.php";
            String metodo = "POST";

            ArrayList<String> datos = new ArrayList<>();
            datos.add(cajaNumControl.getText().toString());
            datos.add(cajaNombre.getText().toString());
            datos.add(cajaPrimerAp.getText().toString());
            datos.add(cajaSegundoAp.getText().toString());
            datos.add(spinnerEdad.getSelectedItem().toString());
            datos.add(spinnerSemestre.getSelectedItem().toString());
            datos.add(carrera);

            AnalizadorJSON analizadorJSON = new AnalizadorJSON();

            new Thread(() -> {
                JSONObject jsonObject = analizadorJSON.peticionHTTP(URL, metodo, datos);

                try {
                    String resultado = jsonObject.getString("consulta");
                    if (resultado.equals("exito")) {
                        runOnUiThread(() -> Toast.makeText(this, "Cambio realizado correctamente", Toast.LENGTH_LONG).show());
                    }
                } catch (JSONException e) {
                    throw new RuntimeException(e);
                }
            }).start();

        } else {
            Log.e("MSJ ->", "Error en la red (wifi)");
        }
    }

    // Obtener índice para el spinner
    private int getIndexFromSpinner(Spinner spinner, String value) {
        for (int i = 0; i < spinner.getCount(); i++) {
            if (spinner.getItemAtPosition(i).toString().equals(value)) {
                return i;
            }
        }
        return 0;
    }
}
