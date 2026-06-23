import { ref, type Ref } from 'vue';

interface UseCountUpReturn {
    value: Ref<number>;
    start: () => void;
}

/**
 * Animate a number from 0 to `end` with an ease-out curve. `start()` is
 * idempotent, so it can be wired to a scroll-reveal trigger safely.
 */
export function useCountUp(end: number, duration = 1600): UseCountUpReturn {
    const value = ref(0);
    let started = false;

    function start(): void {
        if (started || typeof requestAnimationFrame === 'undefined') {
            value.value = end;
            return;
        }
        started = true;

        const startTime = performance.now();
        const tick = (now: number): void => {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            value.value = Math.round(end * eased);
            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    }

    return { value, start };
}
