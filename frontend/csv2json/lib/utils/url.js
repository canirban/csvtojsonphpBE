const ResultUrl =
  process.env.NEXT_PUBLIC_RESULT_URL || 'http://localhost:3000/results/';

const serviceUrl = 'http://localhost:8000/index.php';

export const urlConfig = {
  method: 'post',
  url: serviceUrl,
  headers: {
    'Content-Type': 'text/plain',
  },
};

export default ResultUrl;
