import os, sqlite3, base64, pickle, hmac, hashlib
from flask import Flask, request, jsonify, make_response

SECRET = "sup3r_s1gn1ng_s3cret_a1b2c3"
DB = "/app/ledger.db"

def init_db():
    con = sqlite3.connect(DB); c = con.cursor()
    c.execute("CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY, username TEXT, balance INTEGER)")
    c.execute("CREATE TABLE IF NOT EXISTS config(key TEXT, value TEXT)")
    c.execute("DELETE FROM users"); c.execute("DELETE FROM config")
    c.executemany("INSERT INTO users(username,balance) VALUES(?,?)", [("alice",1200),("bob",300)])
    c.execute("INSERT INTO config(key,value) VALUES('secret',?)", (SECRET,))
    # Access flag — stored in the DB, only reachable via the injection.
    c.execute("INSERT INTO config(key,value) VALUES('flag1',?)", ("FLAG1{blind_sqli_read_the_flag}",))
    con.commit(); con.close()

init_db()
app = Flask(__name__, static_folder="static")

@app.route("/api/status")
def status():
    username = request.args.get("username", "")
    con = sqlite3.connect(DB); c = con.cursor()
    q = "SELECT id FROM users WHERE username = '%s'" % username   # NOTE: string-formatted
    try:
        c.execute(q); row = c.fetchone()
    except Exception:
        row = None
    finally:
        con.close()
    return jsonify(active=bool(row))

@app.route("/")
def index():
    theme = "light"
    prefs = request.cookies.get("prefs")
    if prefs and "." in prefs:
        data_b64, sig = prefs.rsplit(".", 1)
        expected = hmac.new(SECRET.encode(), data_b64.encode(), hashlib.sha256).hexdigest()
        if hmac.compare_digest(expected, sig):
            try:
                obj = pickle.loads(base64.b64decode(data_b64))   # deserialize trusted prefs
                if isinstance(obj, dict):
                    theme = obj.get("theme", "light")
            except Exception:
                pass
    return make_response(
        f"<h1>Ledger</h1><p>theme=<b>{theme}</b></p>"
        f"<p>Account lookup: <code>/api/status?username=alice</code></p>")

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=80, threaded=True)
