package in.zingme.myhostelapp.hosteler;

import android.animation.Animator;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import in.zingme.myhostelapp.hosteler.authenticated.MainActivity;
import in.zingme.myhostelapp.hosteler.prephase.authentication.AuthenticationActivity;
import in.zingme.myhostelapp.hosteler.prephase.wizzard.WizzardActivity;
import in.zingme.myhostelapp.hosteler.volley.MyVolley;

public class Splash extends AppCompatActivity {
    ImageView logo;
    TextView textView;
    ProgressBar progressBar;
    SharedPreferences sharedPreferences;
    SharedPreferences.Editor editor;
    StringRequest pingRequest = new StringRequest(Request.Method.POST, Views.id._177,
            new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    Log.e("NIO-ping", response);
                    try {
                        JSONObject responce = new JSONObject(response);
                        if (responce.getString("ping").equals("success")) {
                            startActivity(new Intent(Splash.this, MainActivity.class));
                        } else {
                            startActivity(new Intent(Splash.this, AuthenticationActivity.class));
                        }
                    } catch (JSONException e) {
                        Toast.makeText(Splash.this, "There is some error", Toast.LENGTH_SHORT).show();
                    }
                }
            },
            new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    //Toast.makeText(Splash.this, "Please check yor Internet Connection", Toast.LENGTH_SHORT).show();
                    Toast.makeText(Splash.this, "OFFLINE", Toast.LENGTH_SHORT).show();
                }
            }) {
        @Override
        public Map<String, String> getHeaders() throws AuthFailureError {
            HashMap headers = new HashMap();
            headers.put("Content-Type", "application/json");
            headers.put("reqty", Views.id._077);
            return headers;
        }

        @Override
        protected Map<String, String> getParams() {
            Map<String, String> params = new HashMap<String, String>();
            params.put("name", "Alif");
            params.put("domain", "http://itsalif.info");
            return params;
        }
    };
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        logo = findViewById(R.id.splash_logo);
        textView = findViewById(R.id.splash_text);
        progressBar = findViewById(R.id.splash_progress);
        sharedPreferences = getSharedPreferences(getPackageName(), Context.MODE_PRIVATE);
    }

    @Override
    protected void onResume() {
        super.onResume();
        textView.setAlpha(0f);
        textView.animate().alphaBy(1f).setDuration(1000).setListener(new Animator.AnimatorListener() {
            @Override
            public void onAnimationStart(Animator animation) {

            }

            @Override
            public void onAnimationEnd(Animator animation) {
                whatNext();
                progressBar.setVisibility(View.VISIBLE);
            }


            @Override
            public void onAnimationCancel(Animator animation) {

            }

            @Override
            public void onAnimationRepeat(Animator animation) {

            }
        });
        Animation hyperspaceJump = AnimationUtils.loadAnimation(this, R.anim.anim_bounce);
        logo.startAnimation(hyperspaceJump);
    }

    private void whatNext() {
        if (sharedPreferences.getBoolean("first_launch", true)) {
            startActivity(new Intent(this, WizzardActivity.class));
        } else {
            if (sharedPreferences.getBoolean("authenticated", false)) {
                startActivity(new Intent(this, MainActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            } else {
                startActivity(new Intent(this, AuthenticationActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            }
        }
    }
}
