package in.zingme.myhostelapp.hosteler.authenticated.history;

import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import in.zingme.myhostelapp.hosteler.R;
import in.zingme.myhostelapp.hosteler.Views;
import in.zingme.myhostelapp.hosteler.appdata.AppData;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.HistoryOuting;
import in.zingme.myhostelapp.hosteler.appdata.room.AppDatabase;
import in.zingme.myhostelapp.hosteler.volley.MyVolley;

public class History extends Fragment {
    View rootView, selectAll, selectDayout, selectLeave;
    ListView listView;
    SwipeRefreshLayout swipeRefreshLayout;
    HistoryListAdapter adapter;
    SwipeRefreshLayout.OnRefreshListener onRefreshListener = new SwipeRefreshLayout.OnRefreshListener() {
        @Override
        public void onRefresh() {
            MyVolley.getInstance(getActivity().getApplicationContext()).getRequestQueue().add(historyRequest);
        }
    };
    StringRequest historyRequest = new StringRequest(Request.Method.POST, Views.id._184, new Response.Listener<String>() {
        @Override
        public void onResponse(String response) {
            Log.e("Volley-authRequest", "RESPONSE" + response);
            try {
                JSONObject jsonObject = new JSONObject(response);
                if (!jsonObject.getBoolean("error")) {
                    if (jsonObject.getString("access_token").equals(AppData.getInstance().getUserCard().getAccessToken())) {
                        final List<HistoryOuting> historyOutingList = new ArrayList<>();
                        JSONObject leavelist = jsonObject.getJSONObject("leave");
                        JSONObject dayoutlist = jsonObject.getJSONObject("dayout");
                        if (leavelist.getInt("list_count") > 0) {
                            JSONArray list = leavelist.getJSONArray("list");
                            for (int i = 0; i < list.length(); i++) {
                                HistoryOuting historyOuting = new HistoryOuting();
                                historyOuting.setId(list.getJSONObject(i).getString("id")+"L");
                                historyOuting.setPlace(list.getJSONObject(i).getString("place"));
                                historyOuting.setPurpose(list.getJSONObject(i).getString("purpose"));
                                historyOuting.setGoingWith(list.getJSONObject(i).getString("going_with"));
                                historyOuting.setDateOut(list.getJSONObject(i).getString("date_out"));
                                historyOuting.setDateIn(list.getJSONObject(i).getString("date_in"));
                                historyOuting.setTimeOut(list.getJSONObject(i).getString("time_out"));
                                historyOuting.setTimeIn(list.getJSONObject(i).getString("time_in"));
                                historyOuting.setSecOutId(list.getJSONObject(i).getString("sec_out_id"));
                                historyOuting.setSecInId(list.getJSONObject(i).getString("sec_in_id"));
                                historyOuting.setLate(Integer.parseInt(list.getJSONObject(i).getString("late")));
                                historyOuting.setPhone(list.getJSONObject(i).getString("phone"));
                                historyOuting.setParentNo(list.getJSONObject(i).getString("parent_no"));
                                historyOuting.setWardenId(list.getJSONObject(i).getString("warden_id"));
                                historyOuting.setRemark(list.getJSONObject(i).getString("remark"));
                                historyOuting.setType("L");
                                historyOutingList.add(historyOuting);
                            }
                        }
                        if (dayoutlist.getInt("list_count") > 0) {
                            JSONArray list = dayoutlist.getJSONArray("list");
                            for (int i = 0; i < list.length(); i++) {
                                HistoryOuting historyOuting = new HistoryOuting();
                                historyOuting.setId(list.getJSONObject(i).getString("id")+"D");
                                historyOuting.setPlace(list.getJSONObject(i).getString("place"));
                                historyOuting.setPurpose(list.getJSONObject(i).getString("purpose"));
                                historyOuting.setGoingWith(list.getJSONObject(i).getString("going_with"));
                                historyOuting.setDateOut(list.getJSONObject(i).getString("date_out"));
                                historyOuting.setDateIn(list.getJSONObject(i).getString("date_in"));
                                historyOuting.setTimeOut(list.getJSONObject(i).getString("time_out"));
                                historyOuting.setTimeIn(list.getJSONObject(i).getString("time_in"));
                                historyOuting.setSecOutId(list.getJSONObject(i).getString("sec_out_id"));
                                historyOuting.setSecInId(list.getJSONObject(i).getString("sec_in_id"));
                                historyOuting.setLate(Integer.parseInt(list.getJSONObject(i).getString("late")));
                                historyOuting.setPhone(list.getJSONObject(i).getString("phone"));
                                historyOuting.setType("D");
                                historyOutingList.add(historyOuting);
                            }
                        }

                        Collections.sort(historyOutingList);
                        AppData.getInstance().setHistoryOutingList(historyOutingList);
                        Log.e("HISTORY", String.valueOf(AppData.getInstance().getHistoryOutingList().size()));
                        new Thread(new Runnable() {
                            @Override
                            public void run() {
                                Log.e("HISTORY-count bf-del rt", String.valueOf(AppData.getInstance().getHistoryOutingList().size()));
                                Log.e("HISTORY-count bf-del db", String.valueOf(AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount()));
                                if (AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount() > 0) {

                                    AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().deleteHistoryOuting();
                                    Log.e("HISTORY-count af-del db", String.valueOf(AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount()));
                                }
                                AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().insertOutingHistory(AppData.getInstance().getHistoryOutingList());
                                Log.e("HISTORY-count af ins db", String.valueOf(AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount()));
                                Log.e("HISTORY-count af ins rt", String.valueOf(AppData.getInstance().getHistoryOutingList().size()));
                            }
                        }).start();

                    } else {
                        Log.i("Volley-statusReq", "token-mismatch");
                    }
                } else {
                    Log.e("Volley-statusReq", jsonObject.getString("errorMessage"));
                }
            } catch (JSONException e) {
                e.printStackTrace();
                Log.e("Volley-statusReq", e.getMessage());
            }
            swipeRefreshLayout.setRefreshing(false);
        selectAll.callOnClick();
        }
    }, new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            if (error.getMessage() != null) Log.e("Volley-statusReq", error.getMessage());
            swipeRefreshLayout.setRefreshing(false);
            new Thread(new Runnable() {
                @Override
                public void run() {
                    try {

                        if (AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount() > 0) {
                            Log.i("Volley-statusReq", "error: " + "loading from DB-offline");
                            AppData.getInstance().setHistoryOutingList(AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOuting());
                        } else {
                            Log.i("Volley-statusReq", "error: " + "No active outings");
                            //AppData.getInstance().setActiveOuting(null);
                        }

                    } catch (NullPointerException e) {
                    }
                }
            }).start();

            selectAll.callOnClick();
        }
    }) {
        @Override
        public Map<String, String> getHeaders() throws AuthFailureError {
            HashMap headers = new HashMap();
            headers.put("reqty", Views.id._084);
            return headers;
        }

        @Override
        protected Map<String, String> getParams() throws AuthFailureError {
            Map<String, String> params = new HashMap<String, String>();
            params.put("user_id", AppData.getInstance().getUserCard().getUserId());
            params.put("access_token", AppData.getInstance().getUserCard().getAccessToken());
            Log.e("Volley-authRequest", "POST" + params);
            return params;
        }
    };


    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        rootView = inflater.inflate(R.layout.fragment_history, container, false);
        return rootView;
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        selectAll = rootView.findViewById(R.id.history_select_all);
        selectDayout = rootView.findViewById(R.id.history_select_dayout);
        selectLeave = rootView.findViewById(R.id.history_select_leave);
        listView = rootView.findViewById(R.id.history_listview);
        selectAll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                adapter = new HistoryListAdapter("all", getActivity().getApplicationContext());
                listView = rootView.findViewById(R.id.history_listview);
                if (AppData.getInstance().getHistoryOutingList()!=null){
                    listView.setAdapter(adapter);
                    adapter.notifyDataSetChanged();
                }
                selectAll.setBackground(getActivity().getDrawable(R.drawable.shape_selector_active));
                selectDayout.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
                selectLeave.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
            }
        });
        selectDayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                adapter = new HistoryListAdapter("dayout", getActivity().getApplicationContext());
                listView = rootView.findViewById(R.id.history_listview);
                if (AppData.getInstance().getHistoryOutingList()!=null){
                    listView.setAdapter(adapter);
                    adapter.notifyDataSetChanged();
                }
                selectAll.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
                selectDayout.setBackground(getActivity().getDrawable(R.drawable.shape_selector_active));
                selectLeave.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
            }
        });
        selectLeave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                adapter = new HistoryListAdapter("leave", getActivity().getApplicationContext());
                listView = rootView.findViewById(R.id.history_listview);
                if (AppData.getInstance().getHistoryOutingList()!=null){
                    listView.setAdapter(adapter);
                    adapter.notifyDataSetChanged();
                }
                selectAll.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
                selectDayout.setBackground(getActivity().getDrawable(R.drawable.shape_selector_inactive));
                selectLeave.setBackground(getActivity().getDrawable(R.drawable.shape_selector_active));
            }
        });
        swipeRefreshLayout = rootView.findViewById(R.id.history_swiperefresh);
        swipeRefreshLayout.setOnRefreshListener(onRefreshListener);
        loadLocal();
    }

    private void loadLocal() {
        new Thread(new Runnable() {
            @Override
            public void run() {
                try {

                    if (AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOutingCount() > 0) {
                        Log.i("Volley-statusReq", "error: " + "loading from DB-offline");
                        AppData.getInstance().setHistoryOutingList(AppDatabase.getInstance(getActivity().getApplicationContext()).userCardDao().getHistoryOuting());
                    }

                } catch (NullPointerException e) {
                }
            }
        }).start();
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                selectAll.callOnClick();
            }
        }, 500);
    }
}
