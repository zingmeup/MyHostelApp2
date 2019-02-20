package in.zingme.myhostelapp.hosteler.appdata;

import java.util.ArrayList;
import java.util.List;

import in.zingme.myhostelapp.hosteler.appdata.datamodels.ActiveOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.DayoutRequest;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.HistoryOuting;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.LeaveRequest;
import in.zingme.myhostelapp.hosteler.appdata.datamodels.UserCard;

public class AppData {
    private static AppData appData;
    UserCard userCard;
    ActiveOuting activeOuting;
    DayoutRequest dayoutRequest;
    LeaveRequest leaveRequest;
    List<HistoryOuting> historyOutingList;

    public List<HistoryOuting> getHistoryOutingList() {
        return historyOutingList;
    }
    AppData(){
        historyOutingList=new ArrayList<>();
    }

    public void setHistoryOutingList(List<HistoryOuting> historyOutingList) {
        this.historyOutingList = historyOutingList;
    }

    static public AppData getInstance(){
        if (appData==null) appData=new AppData();
        return appData;
    }

    public DayoutRequest getDayoutRequest() {
        return dayoutRequest;
    }

    public LeaveRequest getLeaveRequest() {
        return leaveRequest;
    }

    public void setLeaveRequest(LeaveRequest leaveRequest) {
        this.leaveRequest = leaveRequest;
    }

    public void setDayoutRequest(DayoutRequest dayoutRequest) {
        this.dayoutRequest = dayoutRequest;
    }

    public AppData getAppData() {
        return appData;
    }

    public void setAppData(AppData appData) {
        this.appData = appData;
    }

    public UserCard getUserCard() {
        return userCard;
    }

    public void setUserCard(UserCard userCard) {
        this.userCard = userCard;
    }

    public ActiveOuting getActiveOuting() {
        return activeOuting;
    }

    public void setActiveOuting(ActiveOuting activeOuting) {
        this.activeOuting = activeOuting;
    }
}
