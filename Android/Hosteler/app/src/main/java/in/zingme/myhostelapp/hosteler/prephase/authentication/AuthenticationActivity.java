package in.zingme.myhostelapp.hosteler.prephase.authentication;

import android.animation.Animator;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.CardView;
import android.text.TextUtils;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
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

import in.zingme.myhostelapp.hosteler.R;
import in.zingme.myhostelapp.hosteler.Views;
import in.zingme.myhostelapp.hosteler.appdata.AppData;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.Login;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.UserCard;
import in.zingme.myhostelapp.hosteler.appdata.room.AppDatabase;
import in.zingme.myhostelapp.hosteler.authenticated.MainActivity;
import in.zingme.myhostelapp.hosteler.volley.MyVolley;

public class AuthenticationActivity extends AppCompatActivity {
    View errorView;
    ProgressBar progressBar;
    View loginView, userCardView;
    CardView userImage;
    TextView userid, pass, userHostel, userName, userBranch, userCourse, errorText;
    Button login, ack;
    private static final String DATABASE_NAME = "movies_db";
    private AppDatabase appDatabase;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_authentication);
        loginView = findViewById(R.id.auth_layout_login);
        loginView.setVisibility(View.VISIBLE);
        errorView = findViewById(R.id.auth_error_view);
        errorText = findViewById(R.id.auth_error_text);
        userCardView = findViewById(R.id.auth_layout_usercard);
        userid = findViewById(R.id.auth_login_userid);
        pass = findViewById(R.id.auth_login_pass);
        progressBar = findViewById(R.id.auth_progress);
        userCardView.setVisibility(View.GONE);
        login = findViewById(R.id.auth_login_btn);

    }


    @Override
    protected void onResume() {
        super.onResume();

/*        appDatabase = Room.databaseBuilder(getApplicationContext(),
                AppDatabase.class, DATABASE_NAME).fallbackToDestructiveMigration()
                .build();*/
        progressBar.setVisibility(View.INVISIBLE);
        //showUserCard();
        login.setOnClickListener(loginClickListener);

    }
    View.OnClickListener loginClickListener=new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (!(TextUtils.isEmpty(userid.getText()) || TextUtils.isEmpty(pass.getText()))) {
                Login.setUserId(userid.getText().toString().toUpperCase());
                Login.setPass(new String(Base64.encode((pass.getText().toString()).getBytes(), Base64.DEFAULT)));
                progressBar.setVisibility(View.VISIBLE);
                login.setBackground(getDrawable(R.drawable.shape_button_disabled));
                login.setOnClickListener(disabled);
                tryAuthentication();
            }

        }
    };

    View.OnClickListener disabled=new View.OnClickListener() {
        @Override
        public void onClick(View v) {

        }
    };

    private void tryAuthentication() {
        StringRequest authenticationRequest = new StringRequest(Request.Method.POST, Views.id._179, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.e("Volley-authRequest", "RESPONSE" + response);
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    if (!jsonObject.getBoolean("error")) {
                        if (jsonObject.getString("user_id").toUpperCase().equals(Login.getUserId())) {
                            JSONObject userInfo = jsonObject.getJSONObject("user_info");
                            UserCard userCard = new UserCard(
                                    jsonObject.getString("user_id"),
                                    jsonObject.getString("access_token"),
                                    userInfo.getString("user_type"),
                                    userInfo.getString("gender"),
                                    userInfo.getString("branch"),
                                    userInfo.getString("course"),
                                    userInfo.getString("section"),
                                    userInfo.getString("name"),
                                    userInfo.getString("hostel_id"),
                                    userInfo.getString("hostel"),
                                    userInfo.getString("room"),
                                    userInfo.getString("phone"),
                                    userInfo.getString("email"),
                                    userInfo.getString("img"));
                            AppData.getInstance().setUserCard(userCard);
                            showUserCard();
                        }
                    } else {
                        errorText.setText(jsonObject.getString("errorMessage"));
                        errorView.animate().alphaBy(1f).setDuration(2000).setListener(new Animator.AnimatorListener() {
                            @Override
                            public void onAnimationStart(Animator animation) {
                                progressBar.setVisibility(View.INVISIBLE);
                            }

                            @Override
                            public void onAnimationEnd(Animator animation) {
                                new Handler().postDelayed(new Runnable() {
                                    @Override
                                    public void run() {
                                        errorView.animate().alpha(0f).setDuration(3000);
                                    }
                                }, 3000);
                            }

                            @Override
                            public void onAnimationCancel(Animator animation) {

                            }

                            @Override
                            public void onAnimationRepeat(Animator animation) {

                            }
                        });
                        Log.e("Volley-authRequest", "error");
                        login.setBackground(getDrawable(R.drawable.shape_button_primary));
                        login.setOnClickListener(loginClickListener);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    login.setBackground(getDrawable(R.drawable.shape_button_primary));
                    login.setOnClickListener(loginClickListener);
                    progressBar.setVisibility(View.INVISIBLE);
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(AuthenticationActivity.this, "Network error", Toast.LENGTH_SHORT).show();
                login.setBackground(getDrawable(R.drawable.shape_button_primary));
                login.setOnClickListener(loginClickListener);
                progressBar.setVisibility(View.INVISIBLE);
            }
        }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap headers = new HashMap();
                headers.put("reqty", Views.id._079);
                return headers;
            }

            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("user_id", Login.getUserId());
                params.put("pass", Login.getPass());
                Log.e("Volley-authRequest", "POST" + params);
                return params;
            }
        };
        MyVolley.getInstance(getApplicationContext()).addToRequestQueue(authenticationRequest);
    }

    private void showUserCard() {
        final short duration = 1000;
        loginView.animate().alpha(0f).setListener(new Animator.AnimatorListener() {
            @Override
            public void onAnimationStart(Animator animation) {

            }

            @Override
            public void onAnimationEnd(Animator animation) {
                loginView.setVisibility(View.GONE);
                userCardView.setVisibility(View.VISIBLE);
                userCardView.setAlpha(0f);
                userCardView.setVisibility(View.VISIBLE);
                userImage = findViewById(R.id.usercard_img_card);
                userHostel = findViewById(R.id.usercard_hostel);
                ack = findViewById(R.id.usercard_ack);
                userName = findViewById(R.id.usercard_name);
                userBranch = findViewById(R.id.usercard_branch);
                userCourse = findViewById(R.id.usercard_course);
                ImageView img=findViewById(R.id.usercard_image);
                img.setImageBitmap(BitmapFactory.decodeByteArray(Base64.decode(AppData.getInstance().getUserCard().getImg(), Base64.DEFAULT), 0, Base64.decode(AppData.getInstance().getUserCard().getImg(), Base64.DEFAULT).length));
                userHostel.setText(AppData.getInstance().getUserCard().getHostel());
                userName.setText(AppData.getInstance().getUserCard().getName());
                userBranch.setText(AppData.getInstance().getUserCard().getBranch());
                userCourse.setText(AppData.getInstance().getUserCard().getCourse());
                ack.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        new Thread(new Runnable() {
                            @Override
                            public void run() {
                                AppDatabase.getInstance(getApplicationContext()).userCardDao().insertUserCard(AppData.getInstance().getUserCard());
                            }
                        }).start();
                        getSharedPreferences(getPackageName(), Context.MODE_PRIVATE).edit().putBoolean("authenticated", true).apply();
                        startActivity(new Intent(AuthenticationActivity.this, MainActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
                    }
                });
                Animation hyperspaceJump = AnimationUtils.loadAnimation(AuthenticationActivity.this, R.anim.anim_bounce);
                userImage.startAnimation(hyperspaceJump);
                userCardView.animate().alphaBy(1f).setListener(new Animator.AnimatorListener() {
                    @Override
                    public void onAnimationStart(Animator animation) {

                    }

                    @Override
                    public void onAnimationEnd(Animator animation) {
                        userHostel.animate().alphaBy(1f).setDuration(duration).setListener(new Animator.AnimatorListener() {
                            @Override
                            public void onAnimationStart(Animator animation) {

                            }

                            @Override
                            public void onAnimationEnd(Animator animation) {
                                userName.animate().alphaBy(1f).setDuration(duration).setListener(new Animator.AnimatorListener() {
                                    @Override
                                    public void onAnimationStart(Animator animation) {

                                    }

                                    @Override
                                    public void onAnimationEnd(Animator animation) {
                                        userBranch.animate().alphaBy(1f).setDuration(duration).setListener(new Animator.AnimatorListener() {
                                            @Override
                                            public void onAnimationStart(Animator animation) {
                                            }

                                            @Override
                                            public void onAnimationEnd(Animator animation) {
                                                userCourse.animate().alphaBy(1f).setDuration(duration).setListener(new Animator.AnimatorListener() {
                                                    @Override
                                                    public void onAnimationStart(Animator animation) {

                                                    }

                                                    @Override
                                                    public void onAnimationEnd(Animator animation) {
                                                        ack.animate().alphaBy(1f).setDuration(duration);
                                                        progressBar.setVisibility(View.INVISIBLE);

                                                    }

                                                    @Override
                                                    public void onAnimationCancel(Animator animation) {

                                                    }

                                                    @Override
                                                    public void onAnimationRepeat(Animator animation) {

                                                    }
                                                });
                                            }

                                            @Override
                                            public void onAnimationCancel(Animator animation) {

                                            }

                                            @Override
                                            public void onAnimationRepeat(Animator animation) {

                                            }
                                        });
                                    }

                                    @Override
                                    public void onAnimationCancel(Animator animation) {

                                    }

                                    @Override
                                    public void onAnimationRepeat(Animator animation) {

                                    }
                                });
                            }

                            @Override
                            public void onAnimationCancel(Animator animation) {

                            }

                            @Override
                            public void onAnimationRepeat(Animator animation) {

                            }
                        });
                    }

                    @Override
                    public void onAnimationCancel(Animator animation) {

                    }

                    @Override
                    public void onAnimationRepeat(Animator animation) {

                    }
                });
            }

            @Override
            public void onAnimationCancel(Animator animation) {

            }

            @Override
            public void onAnimationRepeat(Animator animation) {

            }
        });

    }
}
