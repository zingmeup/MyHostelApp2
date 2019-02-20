package in.zingme.myhostelapp.hosteler.appdata.datamodels;

public class DayoutRequest {
    String place,purpose,goingWith,phone;

    public DayoutRequest(String place, String purpose, String goingWith, String phone) {
        this.place = place;
        this.purpose = purpose;
        this.goingWith = goingWith;
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

    public String getGoingWith() {
        return goingWith;
    }

    public void setGoingWith(String goingWith) {
        this.goingWith = goingWith;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }
}
