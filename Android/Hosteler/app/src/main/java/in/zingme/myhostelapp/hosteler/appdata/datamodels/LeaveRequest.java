package in.zingme.myhostelapp.hosteler.appdata.datamodels;

public class LeaveRequest {
    String place,purpose,goingWith,dateExpOut,dateExpIn,timeExpOut,phone,parent;

    public LeaveRequest(String place, String purpose, String goingWith, String dateExpOut, String dateExpIn, String timeExpOut, String phone, String parent) {
        this.place = place;
        this.purpose = purpose;
        this.goingWith = goingWith;
        this.dateExpOut = dateExpOut;
        this.dateExpIn = dateExpIn;
        this.timeExpOut = timeExpOut;
        this.phone = phone;
        this.parent = parent;
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

    public String getGoingWith() {
        return goingWith;
    }

    public void setGoingWith(String goingWith) {
        this.goingWith = goingWith;
    }

    public String getDateExpOut() {
        return dateExpOut;
    }

    public void setDateExpOut(String dateExpOut) {
        this.dateExpOut = dateExpOut;
    }

    public String getDateExpIn() {
        return dateExpIn;
    }

    public void setDateExpIn(String dateExpIn) {
        this.dateExpIn = dateExpIn;
    }

    public String getTimeExpOut() {
        return timeExpOut;
    }

    public void setTimeExpOut(String timeExpOut) {
        this.timeExpOut = timeExpOut;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getParent() {
        return parent;
    }

    public void setParent(String parent) {
        this.parent = parent;
    }
}
