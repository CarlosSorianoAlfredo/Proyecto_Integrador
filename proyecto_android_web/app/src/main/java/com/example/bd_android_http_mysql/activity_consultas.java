package com.example.bd_android_http_mysql;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import controlador.AnalizadorJSON;


public class activity_consultas extends Activity {

    RecyclerView recyclerView;
    RecyclerView.Adapter adapter1;
    RecyclerView.LayoutManager layoutManager;

    static ArrayList registros = new ArrayList();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_consultas);

        recyclerView = findViewById(R.id.recyclerview_alumnos);
        recyclerView.setHasFixedSize(true);

        layoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(layoutManager);

        registros.clear();

        new Thread(new Runnable() {
            @Override
            public void run() {
                //String url = "http://10.0.2.2:8888/Semestre_ago_dic_2022/php/App_ABCC/api_rest_MySQL/api_consultas.php";

                String url = "http://10.0.2.2:80/Semestre_Ago_Dic_2024/App_ABCC_Escuela/api_rest_android_escuela/api_mysql_consultas.php";

                String metodo = "POST";

                AnalizadorJSON analizadorJSON = new AnalizadorJSON();
                JSONObject jsonObject = analizadorJSON.peticionHTTPConsultas(url, metodo);
                Log.d("Server Response", String.valueOf(jsonObject));

                try {
                    JSONArray datos1 = jsonObject.getJSONArray("alumnos");

                    Log.i("MSJ=>",datos1.toString());


                    for (int i=0; i<datos1.length(); i++){
                        StringBuilder cadena = new StringBuilder();
                        cadena.append(datos1.getJSONObject(i).getString("nc") + " " +
                                datos1.getJSONObject(i).getString("n") + " " + datos1.getJSONObject(i).getString("pa")+ " " + datos1.getJSONObject(i).getString("sa") + " " +
                                datos1.getJSONObject(i).getString("e") + " " + datos1.getJSONObject(i).getString("s") + " " + datos1.getJSONObject(i).getString("c") );

                        activity_consultas.registros.add(cadena);
                    }


                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            adapter1 = new AdaptadorRegistros(registros);

                            recyclerView.setAdapter(adapter1);
                        }
                    });

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }).start();



    }
}


class AdaptadorRegistros extends RecyclerView.Adapter<AdaptadorRegistros.MyViewHolder>{
    private ArrayList datos;
    public AdaptadorRegistros(ArrayList datos){
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