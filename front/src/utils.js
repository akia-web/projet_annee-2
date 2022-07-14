const getFileImage = (image) => {
  return URL.createObjectURL(image);
};

const loadFileImage = (image) => {
  URL.revokeObjectURL(image);
};

export { getFileImage, loadFileImage };
