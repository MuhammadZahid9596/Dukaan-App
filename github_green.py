"""
github_green.py
Fills your GitHub contribution graph by creating backdated commits.

Requirements:
  - git installed and configured
  - an empty GitHub repo you own (NOT a fork), cloned locally
  - git user.email must match an email verified on your GitHub account

Usage:
  python github_green.py --repo ./my-repo --days 365
  python github_green.py --repo ./my-repo --start 2026-01-01 --end 2026-10-05 --max 6 --skip-weekends --push
"""

import argparse
import os
import random
import subprocess
from datetime import datetime, timedelta


def run(cmd, cwd, env=None):
    subprocess.run(cmd, cwd=cwd, env=env, check=True,
                   stdout=subprocess.DEVNULL, stderr=subprocess.PIPE)


def make_commit(repo, date):
    log_file = os.path.join(repo, "activity.log")
    with open(log_file, "a", encoding="utf-8") as f:
        f.write(f"{date.isoformat()}\n")

    stamp = date.strftime("%Y-%m-%dT%H:%M:%S")
    env = os.environ.copy()
    env["GIT_AUTHOR_DATE"] = stamp
    env["GIT_COMMITTER_DATE"] = stamp

    run(["git", "add", "activity.log"], repo)
    run(["git", "commit", "-m", f"Update {date.strftime('%Y-%m-%d')}"], repo, env)


def main():
    p = argparse.ArgumentParser(description="Fill GitHub contribution graph")
    p.add_argument("--repo", required=True, help="Path to the local git repo")
    p.add_argument("--days", type=int, default=365, help="Days back from today (ignored if --start is set)")
    p.add_argument("--start", help="Start date YYYY-MM-DD")
    p.add_argument("--end", help="End date YYYY-MM-DD (default: today)")
    p.add_argument("--min", type=int, default=0, help="Min commits per day")
    p.add_argument("--max", type=int, default=5, help="Max commits per day")
    p.add_argument("--chance", type=float, default=0.8, help="Probability a day gets commits (0-1)")
    p.add_argument("--skip-weekends", action="store_true", help="No commits on Sat/Sun")
    p.add_argument("--push", action="store_true", help="Push to remote when done")
    args = p.parse_args()

    repo = os.path.abspath(args.repo)
    if not os.path.isdir(os.path.join(repo, ".git")):
        raise SystemExit(f"Not a git repo: {repo}")

    end = datetime.strptime(args.end, "%Y-%m-%d") if args.end else datetime.now()
    start = (datetime.strptime(args.start, "%Y-%m-%d") if args.start
             else end - timedelta(days=args.days))

    total = 0
    day = start
    while day.date() <= end.date():
        if args.skip_weekends and day.weekday() >= 5:
            day += timedelta(days=1)
            continue
        if random.random() < args.chance:
            for _ in range(random.randint(args.min, args.max)):
                ts = day.replace(hour=random.randint(9, 22),
                                 minute=random.randint(0, 59),
                                 second=random.randint(0, 59))
                make_commit(repo, ts)
                total += 1
        day += timedelta(days=1)

    print(f"Created {total} commits from {start.date()} to {end.date()}")

    if args.push:
        run(["git", "push", "origin", "HEAD"], repo)
        print("Pushed to remote.")
    else:
        print("Run 'git push' in the repo to update GitHub.")


if __name__ == "__main__":
    main()
