package com.example.bd_android_http_mysql;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.Network;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import controlador.AnalizadorJSON;

public class activity_bajas extends Activity {
    RecyclerView recyclerView2;
    RecyclerView.Adapter adapter;
    RecyclerView.LayoutManager layoutManager;
    String NumControl;

    static ArrayList registroFiltro = new ArrayList();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bajas);


        recyclerView2 = findViewById(R.id.recyclerview_eliminar);
        recyclerView2.setHasFixedSize(true);

        layoutManager = new LinearLayoutManager(this);
        recyclerView2.setLayoutManager(layoutManager);

        registroFiltro.clear();

    }

    public void CargarAlumnoEliminado(View view){
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
            String URL = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_consultas.php";
            String metodo = "POST";

            NumControl =((EditText)findViewById(R.id.caja_EliminarAlumno)).getText().toString();


            AnalizadorJSON analizadorJSON = new AnalizadorJSON();

            new Thread(new Runnable() {
                @Override
                public void run() {
                    JSONObject jsonObject = analizadorJSON.peticionHTTPConsultasFiltro(URL, metodo, NumControl);

                    try {
                        JSONArray datos = jsonObject.getJSONArray("alumnos");
                        for (int i=0; i<datos.length(); i++){
                            StringBuilder cadena = new StringBuilder();
                            cadena.append(datos.getJSONObject(i).getString("nc") + " " +
                                    datos.getJSONObject(i).getString("n") + " " + datos.getJSONObject(i).getString("pa")+ " " + datos.getJSONObject(i).getString("sa") + " " +
                                    datos.getJSONObject(i).getString("e") + " " + datos.getJSONObject(i).getString("s") + " " + datos.getJSONObject(i).getString("c") );

                            activity_bajas.registroFiltro.add(cadena);
                        }

                            runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    adapter = new AdaptadorRegistros2(registroFiltro);

                                    recyclerView2.setAdapter(adapter);
                                }
                            });



                    } catch (JSONException e) {
                        throw new RuntimeException(e);
                    }
                }
            }).start();



        }else {
            Log.e("MSJ ->", "Error en la red (wifi)");
        }

    }
    public void eliminarAlumno(View view) {
        if (NumControl == null || NumControl.isEmpty()) {
            Toast.makeText(this, "Por favor, ingresa un número de control válido", Toast.LENGTH_SHORT).show();
            return;
        }

        String URL2 = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_bajas.php";
        String metodo = "POST";
        AnalizadorJSON analizadorJSON = new AnalizadorJSON();

        new Thread(() -> {
            try {
                // Enviar petición para eliminar al alumno
                JSONObject jsonObject = analizadorJSON.peticionHTTPBaja(URL2, metodo, NumControl);

                // Leer la respuesta
                String resultado = jsonObject.getString("consulta");
                if ("exito".equalsIgnoreCase(resultado)) {
                    runOnUiThread(() -> {
                        // Eliminar al alumno del RecyclerView
                        registroFiltro.clear();
                        adapter.notifyDataSetChanged();
                        Toast.makeText(this, "Alumno eliminado correctamente", Toast.LENGTH_SHORT).show();
                    });
                } else {
                    runOnUiThread(() -> Toast.makeText(this, "No se encontró el alumno para eliminar", Toast.LENGTH_SHORT).show());
                }
            } catch (JSONException e) {
                e.printStackTrace();
                runOnUiThread(() -> Toast.makeText(this, "Error al procesar la respuesta del servidor", Toast.LENGTH_SHORT).show());
            } catch (Exception e) {
                e.printStackTrace();
                runOnUiThread(() -> Toast.makeText(this, "Error en la conexión con el servidor", Toast.LENGTH_SHORT).show());
            }
        }).start();
    }

}

class AdaptadorRegistros2 extends RecyclerView.Adapter<AdaptadorRegistros2.MyViewHolder>{
    private ArrayList datos;
    public AdaptadorRegistros2(ArrayList datos){
        this.datos = datos;
    }
    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        TextView textView = (TextView) LayoutInflater.from(parent.getContext()).inflate(R.layout.textview_recyclerview, parent, false);
        MyViewHolder myViewHolder = new MyViewHolder(textView);
        return myViewHolder;
    }


    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {
        //holder.textView.setText((Integer) datos.get(position));
        holder.textView.setText((CharSequence) datos.get(position));
    }
    @Override
    public int getItemCount() {
        return datos.size();
    }
    //clase INTERNA
    public static class MyViewHolder extends RecyclerView.ViewHolder{
        public TextView textView;
        public MyViewHolder(TextView t){
            super(t);
            textView = t;
        }
    }
}//Clase AdaptadorRegistros
