# Flags — answer key (SPOILERS)

Each lab holds a `FLAG{...}` reachable only by achieving RCE. If you're solving the labs, **stop here**
and come back to confirm. These are also baked into each lab's `Dockerfile`.

| Lab | Flag |
|-----|------|
| 01 Looking Glass | `FLAG{typ3_juggl1ng_into_a_l34ky_p1pe}` |
| 02 Estate Sale | `FLAG{unserialize_is_a_cod3_execution_sink}` |
| 03 Render Farm | `FLAG{ejs_templates_run_server_side_js}` |
| 04 Ledger | `FLAG{bl1nd_sqli_leaked_the_pickle_key}` |
| 05 Polyfill | `FLAG{__proto___pollution_reached_a_command_gadget}` |

## Verify every lab works

`./verify.sh` builds each lab, runs its reference exploit, and confirms the flag:

```
$ ./verify.sh
== 01-looking-glass  (http://127.0.0.1:8081) ==
  PASS  FLAG{typ3_juggl1ng_into_a_l34ky_p1pe}
...
  passed=5  failed=0  of 5
```

Run one lab: `./verify.sh 03-render-farm`. Exit code is 0 only if every selected lab yields its flag.
Requires docker (with compose), python3, and curl.
