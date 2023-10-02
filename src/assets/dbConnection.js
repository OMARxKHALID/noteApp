import axios from "axios";

export default class dbConnection {
   static url = "http://localhost:9000";

   //delete single Note
   static deleteNote(id) {
      let dataUrl = `${this.url}/Note/${id}`;
      return axios.delete(dataUrl);
   }
   //create/add new Note
   static addNewNote(data) {
      let dataUrl = `${this.url}/Note`;
      return axios.post(dataUrl, data);
   }
}
