import axios from "axios";

export default axios.create({
  baseURL: "https://api.unsplash.com",
  headers: {
    Authorization:
      "Client-ID 2e58bdb6c859998281b0ee9d217f3081136bc3c90889b8bdfeafa9a7b0addf30",
  },
});
