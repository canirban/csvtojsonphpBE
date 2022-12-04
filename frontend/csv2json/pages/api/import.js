// Next.js API route support: https://nextjs.org/docs/api-routes/introduction
import { parseAsFile, parseAsString } from '../../lib/utils/tsv2json';
export default async function handler(req, res) {
  if (req.method == 'GET') {
    try {
      const result = await parseAsFile('data.csv');
      result
        ? res.status(200).json(result)
        : res.status(500).json('Import Failed');
    } catch (e) {
      res.status(500).json(`Import failed. Reason :${e.message}`);
    }
  } else if (req.method == 'POST') {
    try {
      const result = await parseAsString(req.body?.csv);
      result
        ? res.status(200).json(result)
        : res.status(500).json('Import Failed');
    } catch (e) {
      res.status(500).json(`Import failed. Reason :${e.message}`);
    }
  } else {
    res.status(404).json('Invalid Request');
  }
}
