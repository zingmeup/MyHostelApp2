package in.zingme.myhostelapp.hosteler.appdata.datamodels;


import android.arch.persistence.room.ColumnInfo;
import android.arch.persistence.room.Entity;
import android.arch.persistence.room.PrimaryKey;
import android.support.annotation.NonNull;

@Entity(tableName = "history_outing")
public class HistoryOuting implements Comparable {
    @NonNull
    @PrimaryKey
    @ColumnInfo(name = "id")
    String id;
    @ColumnInfo(name = "phone")
    String phone;
    @ColumnInfo(name = "place")
    String place;
    @ColumnInfo(name = "purpose")
    String purpose;
    @ColumnInfo(name = "going_with")
    String goingWith;
    @ColumnInfo(name = "hostel")
    String hostel;
    @ColumnInfo(name = "parent_no")
    String parentNo;
    @ColumnInfo(name = "date_out")
    String dateOut;
    @ColumnInfo(name = "date_in")
    String dateIn;
    @ColumnInfo(name = "time_out")
    String timeOut;
    @ColumnInfo(name = "time_in")
    String timeIn;
    @ColumnInfo(name = "warden_id")
    String wardenId;
    @ColumnInfo(name = "remark")
    String remark;
    @ColumnInfo(name = "sec_out_id")
    String secOutId;
    @ColumnInfo(name = "sec_in_id")
    String secInId;
    @ColumnInfo(name = "late")
    int late;
    @ColumnInfo(name = "type")
    String type;


    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getPlace() {
        return place;
    }

    public void setPlace(String place) {
        this.place = place;
    }

    public String getPurpose() {
        return purpose;
    }

    public void setPurpose(String purpose) {
        this.purpose = purpose;
    }

    public void setGoingWith(String goingWith) {
        this.goingWith = goingWith;
    }

    public String getHostel() {
        return hostel;
    }

    public void setHostel(String hostel) {
        this.hostel = hostel;
    }



    public void setParentNo(String parentNo) {
        this.parentNo = parentNo;
    }



    public void setDateOut(String dateOut) {
        this.dateOut = dateOut;
    }



    public void setDateIn(String dateIn) {
        this.dateIn = dateIn;
    }


    public void setTimeOut(String timeOut) {
        this.timeOut = timeOut;
    }

    public void setTimeIn(String timeIn) {
        this.timeIn = timeIn;
    }

    public void setWardenId(String wardenId) {
        this.wardenId = wardenId;
    }


    public void setRemark(String remark) {
        this.remark = remark;
    }



    public void setSecOutId(String secOutId) {
        this.secOutId = secOutId;
    }

    public void setSecInId(String secInId) {
        this.secInId = secInId;
    }


    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }


    @NonNull
    public String getId() {
        return id;
    }

    public void setId(@NonNull String id) {
        this.id = id;
    }


    public void setLate(int late) {
        this.late = late;
    }

    public String getGoingWith() {
        return goingWith;
    }

    public String getParentNo() {
        return parentNo;
    }

    public String getDateOut() {
        return dateOut;
    }

    public String getDateIn() {
        return dateIn;
    }

    public String getTimeOut() {
        return timeOut;
    }

    public String getTimeIn() {
        return timeIn;
    }

    public String getWardenId() {
        return wardenId;
    }

    public String getRemark() {
        return remark;
    }

    public String getSecOutId() {
        return secOutId;
    }

    public String getSecInId() {
        return secInId;
    }

    public int getLate() {
        return late;
    }

    @Override
    public int compareTo(Object o) {
        return ((HistoryOuting)(o)).getDateOut().compareTo(this.getDateOut());
    }
}