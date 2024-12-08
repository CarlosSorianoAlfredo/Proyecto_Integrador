package controlador;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;

public class AnalizadorJSON {
    private InputStream is = null ;
    private OutputStream os = null;
    private JSONObject jsonObject = null;
    private HttpURLConnection conexion = null;
    private URL url;


    //codigo para la PETICION HTTP
    public JSONObject peticionHTTP(String direccionURL, String metodo, ArrayList<String> datos){
            //----------------PETICION ALTAS--------------
        //cadena JSON --> {"nc":"01", "n":"juan", "e":"20"}    --->Dentro lleva "pares clave-valor"

        String cadenaJSON = "{" +
                "\"nc\":\"" + datos.get(0) + "\", " +
                "\"n\":\"" + datos.get(1) + "\", " +
                "\"pa\":\"" + datos.get(2) + "\", " +
                "\"sa\":\"" + datos.get(3) + "\", " +
                "\"e\":\"" + datos.get(4) + "\", " +
                "\"s\":\"" + datos.get(5) + "\", " +
                "\"c\":\"" + datos.get(6) + "\"" + "}";


        try {
            url = new URL(direccionURL);
            conexion = (HttpURLConnection) url.openConnection();

            //activamos el envio a traves de HTTP
            conexion.setDoOutput(true);

            //Indicar el metodo de envio
            conexion.setRequestMethod(metodo);

            //indicar el tamaño prestablecido o fijo de la cadena a enviar
            conexion.setFixedLengthStreamingMode(cadenaJSON.length());

            //establever formato de peticion
            conexion.setRequestProperty("Content-Type", "application/x-www.form-urlencoded");
            //conexion.setRequestProperty("Cookie", "__test=9f21275160256ad26e74903e0e97f60b");  PRUEBA PARA INFINITYFREE (SACAMOS LA COOKIE PARA ACCEDER)  "NO SE PUDO"

            //preparar el envio de la peticion
            os = new BufferedOutputStream(conexion.getOutputStream());
            os.write(cadenaJSON.getBytes());
            os.flush();
            os.close();

        } catch (MalformedURLException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        //---------recibir RESPUESTA (response)------------

        try {
            is = new BufferedInputStream(conexion.getInputStream());
            BufferedReader br = new BufferedReader(new InputStreamReader(is));

            StringBuilder cadena = new StringBuilder();

            String fila = null;
            while ((fila = br.readLine()) != null ){
                cadena.append(fila+"\n");
            }

            is.close();
            jsonObject = new JSONObject(String.valueOf(cadena));

        } catch (IOException e) {
            throw new RuntimeException(e);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }


        return jsonObject;
    }

    public JSONObject peticionHTTPBaja(String direccionURL, String metodo, String nc) {
        // JSON para la petición de baja --> {"nc":"01"}
        String cadenaJSON = "{\"nc\":\"" + nc + "\"}";

        try {
            url = new URL(direccionURL);
            conexion = (HttpURLConnection) url.openConnection();

            // Activar el envío a través de HTTP
            conexion.setDoOutput(true);

            // Indicar el método de envío
            conexion.setRequestMethod(metodo);

            // Indicar el tamaño prestablecido o fijo de la cadena a enviar
            conexion.setFixedLengthStreamingMode(cadenaJSON.length());

            // Establecer formato de petición
            conexion.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

            // Preparar el envío de la petición
            os = new BufferedOutputStream(conexion.getOutputStream());
            os.write(cadenaJSON.getBytes());
            os.flush();
            os.close();

        } catch (MalformedURLException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        // --------- Recibir RESPUESTA (response) ------------

        try {
            is = new BufferedInputStream(conexion.getInputStream());
            BufferedReader br = new BufferedReader(new InputStreamReader(is));

            StringBuilder cadena = new StringBuilder();
            String fila = null;

            while ((fila = br.readLine()) != null) {
                cadena.append(fila + "\n");
            }

            is.close();
            jsonObject = new JSONObject(cadena.toString());

        } catch (IOException e) {
            throw new RuntimeException(e);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }

        return jsonObject;
    }


    public JSONObject peticionHTTPConsultas(String cadenaURL, String metodo){

        //FILTRO
        //{"nc":"1"}

        try {

            //Enviar PETICION ----------------------------

                /* String filtroCodificado = URLEncoder.encode(String.valueOf(fitro), "UTF-8");
                //completar para busquedas con FILTRO  */

            url = new URL(cadenaURL);
            conexion = (HttpURLConnection) url.openConnection();

            //activa el envio a traves de HTTP
            conexion.setDoOutput(true);

            //indicar el metodo de evio
            conexion.setRequestMethod(metodo);

                /*tamaño preestablecido o fijo para la cadena a enviar
                //conexion.setFixedLengthStreamingMode(cadenaJSON.length());
                //NECESARIO PARA CONSUTLAS CON FILTRO */

            //Establecer el formato de peticion
            conexion.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

            os = new BufferedOutputStream(conexion.getOutputStream());

            /*
            os.write(cadenaJSON.getBytes());
            //NECESARIO PARA CONSUTLAS CON FILTRO */


            os.flush();
            os.close();

        } catch (UnsupportedEncodingException | MalformedURLException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        //Recibir RESPUESTA ----------------------
        try {
            is = new BufferedInputStream(conexion.getInputStream());
            BufferedReader br = new BufferedReader(new InputStreamReader(is));

            StringBuilder cad = new StringBuilder();

            String fila = null;
            while ((fila = br.readLine()) != null ){
                cad.append(fila+"\n");
            }
            is.close();

            String cadena = cad.toString();
            Log.d("--->", cadena);
            jsonObject = new JSONObject(cadena);

        } catch (IOException | JSONException e) {
            e.printStackTrace();
        }

        return  jsonObject;

    }//metodo PETICIONHTTTPCONSULTAS


    public JSONObject peticionHTTPConsultasFiltro(String cadenaURL, String metodo, String filtro) {

        // Crear un JSON con el filtro {"nc":"valor_del_filtro"}
        String cadenaFiltro = "{\"nc\":\"" + filtro + "\"}";
        try {
            url = new URL(cadenaURL);
            conexion = (HttpURLConnection) url.openConnection();

            // Activa el envío a través de HTTP
            conexion.setDoOutput(true);

            // Indicar el método de envío (GET o POST)
            conexion.setRequestMethod(metodo);

            // Establecer el formato de la petición
            conexion.setRequestProperty("Content-Type", "application/json; charset=UTF-8");

            // Indicar el tamaño del contenido (opcional si usamos streaming)
            conexion.setFixedLengthStreamingMode(cadenaFiltro.length());

            // Preparar el flujo de salida y enviar el filtro
            os = new BufferedOutputStream(conexion.getOutputStream());
            os.write(cadenaFiltro.getBytes("UTF-8")); // Asegurar UTF-8
            os.flush();
            os.close();

        } catch (MalformedURLException e) {
            Log.e("Error URL", "Detalles: " + e.getMessage());
            e.printStackTrace();
        } catch (IOException e) {
            Log.e("Error IO", "Detalles: " + e.getMessage());
            e.printStackTrace();
        }

        // Recibir la respuesta del servidor
        try {
            is = new BufferedInputStream(conexion.getInputStream());
            BufferedReader br = new BufferedReader(new InputStreamReader(is, "UTF-8"));

            StringBuilder cadena = new StringBuilder();
            String fila;
            while ((fila = br.readLine()) != null) {
                cadena.append(fila).append("\n");
            }
            is.close();

            // Convertir la respuesta en un objeto JSON
            String respuesta = cadena.toString();
            Log.d("Respuesta del Servidor", respuesta);
            jsonObject = new JSONObject(respuesta);

        } catch (IOException | JSONException e) {
            Log.e("Error Respuesta", "Detalles: " + e.getMessage());
            e.printStackTrace();
        }

        return jsonObject;
    }

}
