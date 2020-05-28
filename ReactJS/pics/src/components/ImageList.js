import "./ImageList.css";
import React from "react";
import ImageCard from "./ImageCard";

const ImageList = (props) => {
  /** Alternative (Pre-Reformatted / Pre-Simplified)
    const images = props.images.map(({image}) => {
    return (
      <img key={image.id} src={image.urls.regular} alt={image.description} />
    );
  });
  */

  /** Pre-Image Card (Using Mapping)
  const images = props.images.map(({ description, id, urls }) => {
    return <img alt={description} key={id} src={urls.regular} />;
  });
  */

  const images = props.images.map((image) => {
    return <ImageCard key={image.id} image={image} />;
  });

  return <div className="image-list">{images}</div>;
};

export default ImageList;
