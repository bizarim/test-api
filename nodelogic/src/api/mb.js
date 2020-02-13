const express = require('express');
const router = express.Router();

router.post('/mAuth', function (req, res) {
    const { Name, No, MSTR } = req.body;
    axios
        .get(`http://web/okurl.php?Name=${Name}&No=${No}`)
        .then(axiosRes => {
            console.log(axiosRes.data.Name);
            console.log(axiosRes.data.No);
            console.log(MSTR);
            res.send('<script>window.close();</script>');
        });
});
