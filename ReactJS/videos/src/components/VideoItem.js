import "./VideoItem.css";
import React from "react";
import ListGroup from "react-bootstrap/ListGroup";

const VideoItem = ({ video, onVideoSelect }) => {
  return (
    <ListGroup.Item onClick={() => onVideoSelect(video)} className="video-item item">
      <img src={video.snippet.thumbnails.medium.url} />
      <span>{video.snippet.title}</span>
    </ListGroup.Item>
  );
};
export default VideoItem;
