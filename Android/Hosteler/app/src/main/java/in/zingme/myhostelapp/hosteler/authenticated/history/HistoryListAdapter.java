package in.zingme.myhostelapp.hosteler.authenticated.history;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import in.zingme.myhostelapp.hosteler.R;
import in.zingme.myhostelapp.hosteler.appdata.AppData;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.HistoryOuting;

class HistoryListAdapter extends BaseAdapter {
    private LayoutInflater inflater;
    String type;
    List<HistoryOuting> dayoutData;
    List<HistoryOuting> leaveData;

    HistoryListAdapter(String type, Context context) {
        this.type = type;
        inflater = (LayoutInflater) context.getSystemService((Context.LAYOUT_INFLATER_SERVICE));

        if (type.equals("dayout")) {
            dayoutData = new ArrayList<>();

            for (HistoryOuting ho : AppData.getInstance().getHistoryOutingList()) {
                if (ho.getType().equals("D")) {
                    dayoutData.add(ho);
                }
            }

        } else if (type.equals("leave")) {
            leaveData = new ArrayList<>();
            for (HistoryOuting ho : AppData.getInstance().getHistoryOutingList()) {
                if (ho.getType().equals("L")) {
                    leaveData.add(ho);
                }
            }
        }
    }

    @Override
    public int getCount() {
        if (this.type.equals("dayout"))return dayoutData.size();
        if (this.type.equals("leave"))return leaveData.size();
        if (this.type.equals("all")) return AppData.getInstance().getHistoryOutingList().size();
        return 0;
    }

    @Override
    public Object getItem(int position) {
        return position;
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        HistoryOuting historyOuting = AppData.getInstance().getHistoryOutingList().get(position);
        AllViewHolder viewHolder = new AllViewHolder();
        if (convertView == null) {
            if (this.type.equals("all")) {
                if (historyOuting.getType().equals("D")) {
                    convertView = inflater.inflate(R.layout.row_history_dayout, null);
                    viewHolder.outdate = convertView.findViewById(R.id.row_history_dateto);
                    viewHolder.late = convertView.findViewById(R.id.row_history_late);
                    viewHolder.place = convertView.findViewById(R.id.row_history_place);
                    viewHolder.purpose = convertView.findViewById(R.id.row_history_purpose);
                    viewHolder.with = convertView.findViewById(R.id.row_history_with);
                    viewHolder.lateLayout = convertView.findViewById(R.id.row_history_late_layout);

                    viewHolder.outdate.setText(historyOuting.getDateOut());
                    if (historyOuting.getLate() > 0) {
                        viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_bottom_error));
                        viewHolder.late.setText(historyOuting.getLate());
                    } else {
                        viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_green));
                        viewHolder.late.setText(" NO");
                    }
                    viewHolder.place.setText(historyOuting.getPlace());
                    viewHolder.purpose.setText(historyOuting.getPurpose());
                    viewHolder.with.setText(inflater.getContext().getResources().getStringArray(R.array.going_with)[Integer.parseInt(historyOuting.getGoingWith())]);

                } else if (historyOuting.getType().equals("L")) {
                    convertView = inflater.inflate(R.layout.row_history_leave, null);
                    viewHolder.outdate = convertView.findViewById(R.id.row_history_datefrom);
                    viewHolder.indate = convertView.findViewById(R.id.row_history_dateto);
                    viewHolder.late = convertView.findViewById(R.id.row_history_late);
                    viewHolder.place = convertView.findViewById(R.id.row_history_place);
                    viewHolder.purpose = convertView.findViewById(R.id.row_history_purpose);
                    viewHolder.with = convertView.findViewById(R.id.row_history_with);
                    viewHolder.lateLayout = convertView.findViewById(R.id.row_history_late_layout);

                    viewHolder.indate.setText(historyOuting.getDateIn());
                    viewHolder.outdate.setText(historyOuting.getDateOut());
                    if (historyOuting.getLate() > 0) {
                        viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_bottom_error));
                        viewHolder.late.setText(historyOuting.getLate());
                    } else {
                        viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_green));
                        viewHolder.late.setText(" NO");
                    }
                    viewHolder.place.setText(historyOuting.getPlace());
                    viewHolder.purpose.setText(historyOuting.getPurpose());
                    viewHolder.with.setText(inflater.getContext().getResources().getStringArray(R.array.going_with)[Integer.parseInt(historyOuting.getGoingWith())]);
                }
            } else if (this.type.equals("dayout")) {
                Log.e("HISTORY-adapter", "yes req is a dayout");
                HistoryOuting dayoutItem = dayoutData.get(position);
                convertView = inflater.inflate(R.layout.row_history_dayout, null);
                viewHolder.outdate = convertView.findViewById(R.id.row_history_dateto);
                viewHolder.late = convertView.findViewById(R.id.row_history_late);
                viewHolder.place = convertView.findViewById(R.id.row_history_place);
                viewHolder.purpose = convertView.findViewById(R.id.row_history_purpose);
                viewHolder.with = convertView.findViewById(R.id.row_history_with);
                viewHolder.lateLayout = convertView.findViewById(R.id.row_history_late_layout);

                viewHolder.outdate.setText(dayoutItem.getDateOut());
                if (historyOuting.getLate() > 0) {
                    viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_bottom_error));
                    viewHolder.late.setText(dayoutItem.getLate());
                } else {
                    viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_green));
                    viewHolder.late.setText(" NO");
                }
                viewHolder.place.setText(dayoutItem.getPlace());
                viewHolder.purpose.setText(dayoutItem.getPurpose());
                viewHolder.with.setText(inflater.getContext().getResources().getStringArray(R.array.going_with)[Integer.parseInt(historyOuting.getGoingWith())]);

            } else if (this.type.equals("leave")) {
                Log.e("HISTORY-adapter", "yes req is a leave");
                HistoryOuting leaveItem = leaveData.get(position);
                convertView = inflater.inflate(R.layout.row_history_leave, null);
                viewHolder.outdate = convertView.findViewById(R.id.row_history_datefrom);
                viewHolder.indate = convertView.findViewById(R.id.row_history_dateto);
                viewHolder.late = convertView.findViewById(R.id.row_history_late);
                viewHolder.place = convertView.findViewById(R.id.row_history_place);
                viewHolder.purpose = convertView.findViewById(R.id.row_history_purpose);
                viewHolder.with = convertView.findViewById(R.id.row_history_with);
                viewHolder.lateLayout = convertView.findViewById(R.id.row_history_late_layout);
                viewHolder.indate.setText(leaveItem.getDateIn());
                viewHolder.outdate.setText(leaveItem.getDateOut());
                if (historyOuting.getLate() > 0) {
                    viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_bottom_error));
                    viewHolder.late.setText(leaveItem.getLate());
                } else {
                    viewHolder.lateLayout.setBackground(inflater.getContext().getDrawable(R.drawable.shape_green));
                    viewHolder.late.setText(" NO");
                }
                viewHolder.place.setText(leaveItem.getPlace());
                viewHolder.purpose.setText(leaveItem.getPurpose());
                viewHolder.with.setText(inflater.getContext().getResources().getStringArray(R.array.going_with)[Integer.parseInt(historyOuting.getGoingWith())]);
            }
            convertView.setAlpha(0f);
            convertView.animate().alphaBy(1f).setDuration(500);

        }
        return convertView;
    }

    private class AllViewHolder {
        TextView outdate, indate, place, purpose, with, late; View lateLayout;
    }
}